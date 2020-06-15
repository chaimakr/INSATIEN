<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200615003436 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE request_from_teacher DROP FOREIGN KEY FK_2D821CA241807E1D');
        $this->addSql('DROP INDEX IDX_2D821CA241807E1D ON request_from_teacher');
        $this->addSql('ALTER TABLE request_from_teacher CHANGE teacher_id student_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE request_from_teacher ADD CONSTRAINT FK_2D821CA2CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2D821CA2CB944F1A ON request_from_teacher (student_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE request_from_teacher DROP FOREIGN KEY FK_2D821CA2CB944F1A');
        $this->addSql('DROP INDEX IDX_2D821CA2CB944F1A ON request_from_teacher');
        $this->addSql('ALTER TABLE request_from_teacher CHANGE student_id teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE request_from_teacher ADD CONSTRAINT FK_2D821CA241807E1D FOREIGN KEY (teacher_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2D821CA241807E1D ON request_from_teacher (teacher_id)');
    }
}
