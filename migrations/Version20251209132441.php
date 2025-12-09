<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251209132441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personne ADD restaurant_id_id INT NOT NULL, DROP roles');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EF35592D86 FOREIGN KEY (restaurant_id_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_FCEC9EF35592D86 ON personne (restaurant_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EF35592D86');
        $this->addSql('DROP INDEX IDX_FCEC9EF35592D86 ON personne');
        $this->addSql('ALTER TABLE personne ADD roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, DROP restaurant_id_id');
    }
}
