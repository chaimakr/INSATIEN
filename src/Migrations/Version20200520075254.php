<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200520075254 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE class_group (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, title VARCHAR(70) NOT NULL, INDEX IDX_8B1765F37E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE class_group_student (class_group_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_B3E48D054A9A1217 (class_group_id), INDEX IDX_B3E48D05CB944F1A (student_id), PRIMARY KEY(class_group_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, class_group_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, evalution INT NOT NULL, INDEX IDX_B6F7494E7E3C61F9 (owner_id), INDEX IDX_B6F7494E4A9A1217 (class_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT NOT NULL, grade VARCHAR(70) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE class_group ADD CONSTRAINT FK_8B1765F37E3C61F9 FOREIGN KEY (owner_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE class_group_student ADD CONSTRAINT FK_B3E48D054A9A1217 FOREIGN KEY (class_group_id) REFERENCES class_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE class_group_student ADD CONSTRAINT FK_B3E48D05CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E4A9A1217 FOREIGN KEY (class_group_id) REFERENCES class_group (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teacher ADD CONSTRAINT FK_B0F6A6D5BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covoiturage CHANGE more_details more_details VARCHAR(65532) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE class_group_student DROP FOREIGN KEY FK_B3E48D054A9A1217');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E4A9A1217');
        $this->addSql('ALTER TABLE class_group_student DROP FOREIGN KEY FK_B3E48D05CB944F1A');
        $this->addSql('ALTER TABLE class_group DROP FOREIGN KEY FK_8B1765F37E3C61F9');
        $this->addSql('DROP TABLE class_group');
        $this->addSql('DROP TABLE class_group_student');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('ALTER TABLE covoiturage CHANGE more_details more_details MEDIUMTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user DROP type');
    }
}
