<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200622102130 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quiz_session (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, status SMALLINT NOT NULL, INDEX IDX_C21E7874853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_session ADD CONSTRAINT FK_C21E7874853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz_try ADD quiz_session_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quiz_try ADD CONSTRAINT FK_D3571A832850CBE3 FOREIGN KEY (quiz_session_id) REFERENCES quiz_session (id)');
        $this->addSql('CREATE INDEX IDX_D3571A832850CBE3 ON quiz_try (quiz_session_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz_try DROP FOREIGN KEY FK_D3571A832850CBE3');
        $this->addSql('DROP TABLE quiz_session');
        $this->addSql('DROP INDEX IDX_D3571A832850CBE3 ON quiz_try');
        $this->addSql('ALTER TABLE quiz_try DROP quiz_session_id');
    }
}
