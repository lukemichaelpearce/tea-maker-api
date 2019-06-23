<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\TeamMember;
use App\Event\TeaChange;
use App\Event\TeaChangeEvent;
use App\Interfaces\TeamDataGeneratorInterface;
use App\Interfaces\TeamMemberSelectorInterface;
use http\Exception\RuntimeException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class TeamAPIController
 * @package App\Controller
 */
class TeamAPIController
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var TeamDataGeneratorInterface
     */
    protected $teamDataGenerator;

    /**
     * TeamAPIController constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
     * @param TeamDataGeneratorInterface $teamDataGenerator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        SerializerInterface $serializer,
        TeamDataGeneratorInterface $teamDataGenerator
    )
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->teamDataGenerator = $teamDataGenerator;
    }

    /**
     * List all teams
     * @Route("/team", name="list_team", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        try {
            // fetch the team data
            $teams = $this->entityManager->getRepository(Team::class)
                                         ->findAll();

            $teamData = [];
            if (count($teams)) {
                foreach ($teams as $team) {
                    // Hydrate any team data
                    $teamData[] = $this->teamDataGenerator->generate($team);
                }
            }

            // return success response
            return JsonResponse::create(
                [
                    "teams" => $teamData,
                    "count" => count($teams),
                ],
                JsonResponse::HTTP_OK
            );
        } catch (\Exception $e) {
            // catch/log any errors and return a 400 response
            $this->logger->error("Error getting team index", [
                "message" => $e->getMessage(),
            ]);

            // friendly response to user
            return JsonResponse::create(
                [
                    "message" => "There was an error accessing the team data"
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Create a new team
     * @Route("/team", name="create_team", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        // decode request data
        $data = json_decode($request->getContent());

        try {
            // create the new team entity and save
            $team = new Team();
            $team->setName($data->name);

            $this->entityManager->persist($team);
            $this->entityManager->flush();

            // return success response
            return JsonResponse::create(
                [
                    "team" => $this->teamDataGenerator->generate($team),
                    "message" => "Successfully added a team"
                ],
                JsonResponse::HTTP_CREATED
            );
        } catch (\Exception $e) {
            // catch/log any errors and return a 400 response
            $this->logger->error("Error creating team", [
                "message" => $e->getMessage(),
            ]);

            // friendly response to user
            return JsonResponse::create(
                [
                    "message" => "There was an error creating the team"
                ],
                JsonResponse::HTTP_CREATED
            );
        }
    }

    /**
     * Change the current team member
     * @Route("/team/{teamId}/change-current-member", name="change_current_team_member", methods={"PUT"}, requirements={"teamId"="\d+"})
     * @param $teamId
     * @param TeamMemberSelectorInterface $teamMemberSelector
     * @param EventDispatcherInterface $dispatcher
     * @return static
     */
    public function changeCurrentMemberForTeam($teamId, TeamMemberSelectorInterface $teamMemberSelector, EventDispatcherInterface $dispatcher)
    {
        try {
            // get the team data
            $team = $this->entityManager->getRepository(Team::class)
                ->find($teamId);

            if (!$team instanceof Team) {
                throw new NotFoundHttpException("Team does not exist.");
            }

            // check we have enough team members available
            if (!$team->getTeamMembers()->count()) {
                throw new NotFoundHttpException(sprintf('There were no team members associated with %s.', $team->getName()));
            }

            $selectableMembers = $team->getTeamMembers()->toArray();

            // if a current team member already exists then filter them out, as no one wants to make a cup of tea twice!
            if ($team->getCurrentTeamMember() instanceof TeamMember) {
                $selectableMembers = array_filter($selectableMembers, function ($member) use ($team) {
                    return $member->getId() !== $team->getCurrentTeamMember()->getId();
                });
            }

            // final check to ensure we still have enough team members
            if (!count($selectableMembers)) {
                throw new RuntimeException('There are no team members to select from after filtering');
            }

            // generate a new random team member
            $newCurrentTeamMember = $teamMemberSelector->select($selectableMembers);

            if (!$newCurrentTeamMember instanceof TeamMember) {
                throw new RuntimeException('Current Team Member is not an instance of TeamMember');
            }

            // set the new team member and save
            $team->setCurrentTeamMember($newCurrentTeamMember);

            $this->entityManager->persist($team);
            $this->entityManager->flush();

            // fire off a tea change event to add history
            $event = new TeaChangeEvent($team);
            $dispatcher->dispatch(TeaChangeEvent::NAME, $event);

            // return success response
            return JsonResponse::create(
                [
                    "team" => $this->teamDataGenerator->generate($team),
                    "message" => "Successfully changed the new tea maker"
                ],
                JsonResponse::HTTP_OK
            );
        } catch (\Exception $e) {
            // catch/log any errors and return a 400 response
            $this->logger->error("Error changing the current team member", [
                "message" => $e->getMessage(),
            ]);

            // friendly response to user
            return JsonResponse::create(
                [
                    "message" => "There was an error setting the new tea maker"
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}