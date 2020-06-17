<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617203814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vote_question (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, question_id INT DEFAULT NULL, INDEX IDX_48A8E067A76ED395 (user_id), INDEX IDX_48A8E0671E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote_response (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, response_id INT DEFAULT NULL, INDEX IDX_C024A2D2A76ED395 (user_id), INDEX IDX_C024A2D2FBF32840 (response_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote_question ADD CONSTRAINT FK_48A8E067A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vote_question ADD CONSTRAINT FK_48A8E0671E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE vote_response ADD CONSTRAINT FK_C024A2D2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vote_response ADD CONSTRAINT FK_C024A2D2FBF32840 FOREIGN KEY (response_id) REFERENCES response (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vote_question');
        $this->addSql('DROP TABLE vote_response');
    }
}
