<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260106125720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD tables_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D85405FD2 FOREIGN KEY (tables_id) REFERENCES `table` (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D85405FD2 ON commande (tables_id)');
        $this->addSql('ALTER TABLE personne CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D85405FD2');
        $this->addSql('DROP INDEX IDX_6EEAA67D85405FD2 ON commande');
        $this->addSql('ALTER TABLE commande DROP tables_id');
        $this->addSql('ALTER TABLE personne CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
