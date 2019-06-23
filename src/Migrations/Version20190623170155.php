<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190623170155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tea_maker_history DROP INDEX UNIQ_418748F3296CD8AE, ADD INDEX IDX_418748F3296CD8AE (team_id)');
        $this->addSql('ALTER TABLE tea_maker_history DROP INDEX UNIQ_418748F3C292CD19, ADD INDEX IDX_418748F3C292CD19 (team_member_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tea_maker_history DROP INDEX IDX_418748F3296CD8AE, ADD UNIQUE INDEX UNIQ_418748F3296CD8AE (team_id)');
        $this->addSql('ALTER TABLE tea_maker_history DROP INDEX IDX_418748F3C292CD19, ADD UNIQUE INDEX UNIQ_418748F3C292CD19 (team_member_id)');
    }
}
