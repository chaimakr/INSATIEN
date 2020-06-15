<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200614234902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE class_group (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, title VARCHAR(70) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_8B1765F37E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE class_group_user (class_group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_12C47F9B4A9A1217 (class_group_id), INDEX IDX_12C47F9BA76ED395 (user_id), PRIMARY KEY(class_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_mail (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(30) NOT NULL, email VARCHAR(100) NOT NULL, subject VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE covoiturage (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, more_details VARCHAR(255) DEFAULT NULL, departure_point VARCHAR(50) NOT NULL, arrival_point VARCHAR(50) NOT NULL, type VARCHAR(20) NOT NULL, departure_time INT DEFAULT NULL, return_time INT DEFAULT NULL, INDEX IDX_28C79E897E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map_point (id INT AUTO_INCREMENT NOT NULL, covoiturage_id INT NOT NULL, x DOUBLE PRECISION NOT NULL, y DOUBLE PRECISION NOT NULL, INDEX IDX_3753BC4862671590 (covoiturage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, date DATETIME NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_CFBDFA147E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, class_id INT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(70) NOT NULL, date DATETIME NOT NULL, content LONGTEXT NOT NULL, evaluation INT DEFAULT NULL, INDEX IDX_B6F7494EEA000B10 (class_id), INDEX IDX_B6F7494E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request_from_student (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, class_group_id INT DEFAULT NULL, INDEX IDX_2A571544CB944F1A (student_id), INDEX IDX_2A5715444A9A1217 (class_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request_from_teacher (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, class_group_id INT DEFAULT NULL, INDEX IDX_2D821CA241807E1D (teacher_id), INDEX IDX_2D821CA24A9A1217 (class_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, quesion_id INT NOT NULL, owner_id INT NOT NULL, content LONGTEXT NOT NULL, evaluation INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_3E7B0BFB73DE463F (quesion_id), INDEX IDX_3E7B0BFB7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(30) NOT NULL, last_name VARCHAR(30) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, confirmation_code VARCHAR(255) NOT NULL, register_as VARCHAR(20) NOT NULL, pdp_path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE class_group ADD CONSTRAINT FK_8B1765F37E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE class_group_user ADD CONSTRAINT FK_12C47F9B4A9A1217 FOREIGN KEY (class_group_id) REFERENCES class_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE class_group_user ADD CONSTRAINT FK_12C47F9BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E897E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE map_point ADD CONSTRAINT FK_3753BC4862671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA147E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EEA000B10 FOREIGN KEY (class_id) REFERENCES class_group (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request_from_student ADD CONSTRAINT FK_2A571544CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request_from_student ADD CONSTRAINT FK_2A5715444A9A1217 FOREIGN KEY (class_group_id) REFERENCES class_group (id)');
        $this->addSql('ALTER TABLE request_from_teacher ADD CONSTRAINT FK_2D821CA241807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request_from_teacher ADD CONSTRAINT FK_2D821CA24A9A1217 FOREIGN KEY (class_group_id) REFERENCES class_group (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB73DE463F FOREIGN KEY (quesion_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE class_group_user DROP FOREIGN KEY FK_12C47F9B4A9A1217');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EEA000B10');
        $this->addSql('ALTER TABLE request_from_student DROP FOREIGN KEY FK_2A5715444A9A1217');
        $this->addSql('ALTER TABLE request_from_teacher DROP FOREIGN KEY FK_2D821CA24A9A1217');
        $this->addSql('ALTER TABLE map_point DROP FOREIGN KEY FK_3753BC4862671590');
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB73DE463F');
        $this->addSql('ALTER TABLE class_group DROP FOREIGN KEY FK_8B1765F37E3C61F9');
        $this->addSql('ALTER TABLE class_group_user DROP FOREIGN KEY FK_12C47F9BA76ED395');
        $this->addSql('ALTER TABLE covoiturage DROP FOREIGN KEY FK_28C79E897E3C61F9');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA147E3C61F9');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E7E3C61F9');
        $this->addSql('ALTER TABLE request_from_student DROP FOREIGN KEY FK_2A571544CB944F1A');
        $this->addSql('ALTER TABLE request_from_teacher DROP FOREIGN KEY FK_2D821CA241807E1D');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB7E3C61F9');
        $this->addSql('DROP TABLE class_group');
        $this->addSql('DROP TABLE class_group_user');
        $this->addSql('DROP TABLE contact_mail');
        $this->addSql('DROP TABLE covoiturage');
        $this->addSql('DROP TABLE map_point');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE request_from_student');
        $this->addSql('DROP TABLE request_from_teacher');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE response');
        $this->addSql('DROP TABLE user');
    }
}
