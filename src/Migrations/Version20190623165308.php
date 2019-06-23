<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190623165308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, current_team_member_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C4E0A61F8C87B0F7 (current_team_member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tea_maker_history (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, team_member_id INT NOT NULL, created DATETIME NOT NULL, UNIQUE INDEX UNIQ_418748F3296CD8AE (team_id), UNIQUE INDEX UNIQ_418748F3C292CD19 (team_member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_member (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6FFBDA1296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F8C87B0F7 FOREIGN KEY (current_team_member_id) REFERENCES team_member (id)');
        $this->addSql('ALTER TABLE tea_maker_history ADD CONSTRAINT FK_418748F3296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE tea_maker_history ADD CONSTRAINT FK_418748F3C292CD19 FOREIGN KEY (team_member_id) REFERENCES team_member (id)');
        $this->addSql('ALTER TABLE team_member ADD CONSTRAINT FK_6FFBDA1296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tea_maker_history DROP FOREIGN KEY FK_418748F3296CD8AE');
        $this->addSql('ALTER TABLE team_member DROP FOREIGN KEY FK_6FFBDA1296CD8AE');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F8C87B0F7');
        $this->addSql('ALTER TABLE tea_maker_history DROP FOREIGN KEY FK_418748F3C292CD19');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tea_maker_history');
        $this->addSql('DROP TABLE team_member');
    }
}
