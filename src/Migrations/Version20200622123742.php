<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200622123742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz_try DROP FOREIGN KEY FK_D3571A83853CD175');
        $this->addSql('DROP INDEX IDX_D3571A83853CD175 ON quiz_try');
        $this->addSql('ALTER TABLE quiz_try DROP quiz_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz_try ADD quiz_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quiz_try ADD CONSTRAINT FK_D3571A83853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D3571A83853CD175 ON quiz_try (quiz_id)');
    }
}
