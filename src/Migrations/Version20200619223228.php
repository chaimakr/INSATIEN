<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619223228 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, class_id INT DEFAULT NULL, INDEX IDX_A412FA92EA000B10 (class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_user (quiz_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_47622A12853CD175 (quiz_id), INDEX IDX_47622A12A76ED395 (user_id), PRIMARY KEY(quiz_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_answer (id INT AUTO_INCREMENT NOT NULL, quiz_question_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, valid TINYINT(1) NOT NULL, INDEX IDX_3799BA7C3101E51F (quiz_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_6033B00B853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_try (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, INDEX IDX_D3571A83CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_try_quiz_answer (quiz_try_id INT NOT NULL, quiz_answer_id INT NOT NULL, INDEX IDX_4466C910C0105EB6 (quiz_try_id), INDEX IDX_4466C910AC5339E1 (quiz_answer_id), PRIMARY KEY(quiz_try_id, quiz_answer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92EA000B10 FOREIGN KEY (class_id) REFERENCES class_group (id)');
        $this->addSql('ALTER TABLE quiz_user ADD CONSTRAINT FK_47622A12853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_user ADD CONSTRAINT FK_47622A12A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_answer ADD CONSTRAINT FK_3799BA7C3101E51F FOREIGN KEY (quiz_question_id) REFERENCES quiz_question (id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz_try ADD CONSTRAINT FK_D3571A83CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz_try_quiz_answer ADD CONSTRAINT FK_4466C910C0105EB6 FOREIGN KEY (quiz_try_id) REFERENCES quiz_try (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_try_quiz_answer ADD CONSTRAINT FK_4466C910AC5339E1 FOREIGN KEY (quiz_answer_id) REFERENCES quiz_answer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz_user DROP FOREIGN KEY FK_47622A12853CD175');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE quiz_try_quiz_answer DROP FOREIGN KEY FK_4466C910AC5339E1');
        $this->addSql('ALTER TABLE quiz_answer DROP FOREIGN KEY FK_3799BA7C3101E51F');
        $this->addSql('ALTER TABLE quiz_try_quiz_answer DROP FOREIGN KEY FK_4466C910C0105EB6');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_user');
        $this->addSql('DROP TABLE quiz_answer');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE quiz_try');
        $this->addSql('DROP TABLE quiz_try_quiz_answer');
    }
}
