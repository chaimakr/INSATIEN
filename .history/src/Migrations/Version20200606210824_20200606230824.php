<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606210824 extends AbstractMigration
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
        $this->addSql('CREATE TABLE class_group_user (class_group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_12C47F9B4A9A1217 (class_group_id), INDEX IDX_12C47F9BA76ED395 (user_id), PRIMARY KEY(class_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, class_id INT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(70) NOT NULL, content LONGTEXT NOT NULL, evaluation INT DEFAULT NULL, INDEX IDX_B6F7494EEA000B10 (class_id), INDEX IDX_B6F7494E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, quesion_id INT NOT NULL, owner_id INT NOT NULL, content LONGTEXT NOT NULL, evaluation INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_3E7B0BFB73DE463F (quesion_id), INDEX IDX_3E7B0BFB7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE class_group ADD CONSTRAINT FK_8B1765F37E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE class_group_user ADD CONSTRAINT FK_12C47F9B4A9A1217 FOREIGN KEY (class_group_id) REFERENCES class_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE class_group_user ADD CONSTRAINT FK_12C47F9BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EEA000B10 FOREIGN KEY (class_id) REFERENCES class_group (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB73DE463F FOREIGN KEY (quesion_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE covoiturage CHANGE more_details more_details VARCHAR(65532) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE class_group_user DROP FOREIGN KEY FK_12C47F9B4A9A1217');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EEA000B10');
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB73DE463F');
        $this->addSql('DROP TABLE class_group');
        $this->addSql('DROP TABLE class_group_user');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE response');
        $this->addSql('ALTER TABLE covoiturage CHANGE more_details more_details MEDIUMTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
