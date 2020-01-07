<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191231163207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA147D2D84D5');
        $this->addSql('DROP INDEX IDX_CFBDFA147D2D84D5 ON note');
        $this->addSql('ALTER TABLE note DROP professor_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note ADD professor_id INT NOT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA147D2D84D5 FOREIGN KEY (professor_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA147D2D84D5 ON note (professor_id)');
    }
}
