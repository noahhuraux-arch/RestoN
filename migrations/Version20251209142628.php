<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251209142628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD serveur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DB8F06499 FOREIGN KEY (serveur_id) REFERENCES personne (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DB8F06499 ON commande (serveur_id)');
        $this->addSql('ALTER TABLE personne CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DB8F06499');
        $this->addSql('DROP INDEX IDX_6EEAA67DB8F06499 ON commande');
        $this->addSql('ALTER TABLE commande DROP serveur_id');
        $this->addSql('ALTER TABLE personne CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
