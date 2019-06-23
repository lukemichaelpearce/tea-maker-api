<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\TeamMember;
use App\Event\TeaChangeEvent;
use App\Interfaces\TeamDataGeneratorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TeamMemberAPIController
 * @package App\Controller
 */
class TeamMemberAPIController
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
     * TeamMemberAPIController constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
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
     * Create a new team member
     * @Route("/team/{teamId}/add-member", name="create_team_member", methods={"POST"}, requirements={"teamId"="\d+"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request, $teamId, EventDispatcherInterface $dispatcher): JsonResponse
    {
        // decode request data
        $data = json_decode($request->getContent());

        try {
            // get the team data
            $team = $this->entityManager->getRepository(Team::class)
                ->find($teamId);

            if (!$team instanceof Team) {
                throw new NotFoundHttpException("Team does not exist.");
            }

            // Create the new Team Member entity and set data
            $teamMember = new TeamMember();
            $teamMember->setName($data->name);

            // if there are no team members then automatically default to the first member
            $teaChangeEventRequired = false;
            if (!$team->getTeamMembers()->count()) {
                $team->setCurrentTeamMember($teamMember);
                $teaChangeEventRequired = true;
            }

            // add the team member relation to the team
            $team->addTeamMember($teamMember);

            // save both team and team member entities
            $this->entityManager->persist($team);
            $this->entityManager->persist($teamMember);
            $this->entityManager->flush();

            if ($teaChangeEventRequired) {
                // fire off a tea change event to add history
                $event = new TeaChangeEvent($team);
                $dispatcher->dispatch(TeaChangeEvent::NAME, $event);
            }

            // return success response
            return JsonResponse::create(
                [
                    "team" => $this->teamDataGenerator->generate($team),
                    "message" => sprintf("Successfully added a team member for %s", $team->getName())
                ],
                JsonResponse::HTTP_CREATED
            );
        } catch (\Exception $e) {
            // catch/log any errors and return a 400 response
            $this->logger->error("Error creating team member", [
                "message" => $e->getMessage(),
            ]);

            // friendly response to user
            return JsonResponse::create(
                [
                    "message" => "There was an error creating the team member."
                ],
                JsonResponse::HTTP_CREATED
            );
        }
    }
}