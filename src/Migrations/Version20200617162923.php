<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617162923 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB73DE463F');
        $this->addSql('DROP INDEX IDX_3E7B0BFB73DE463F ON response');
        $this->addSql('ALTER TABLE response CHANGE quesion_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_3E7B0BFB1E27F6BF ON response (question_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB1E27F6BF');
        $this->addSql('DROP INDEX IDX_3E7B0BFB1E27F6BF ON response');
        $this->addSql('ALTER TABLE response CHANGE question_id quesion_id INT NOT NULL');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB73DE463F FOREIGN KEY (quesion_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3E7B0BFB73DE463F ON response (quesion_id)');
    }
}
