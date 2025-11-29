<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251129164147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE horaire ADD jour VARCHAR(10) NOT NULL, ADD ouverture_midi TIME DEFAULT NULL, ADD fermeture_midi TIME DEFAULT NULL, ADD ouverture_soir TIME DEFAULT NULL, ADD fermeture_soir TIME DEFAULT NULL, ADD ferme TINYINT(1) DEFAULT NULL, ADD restaurant_id INT DEFAULT NULL, DROP lundi, DROP mardi, DROP mercredi, DROP jeudi, DROP vendredi, DROP samedi, DROP dimanche');
        $this->addSql('ALTER TABLE horaire ADD CONSTRAINT FK_BBC83DB6B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_BBC83DB6B1E7706E ON horaire (restaurant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE horaire DROP FOREIGN KEY FK_BBC83DB6B1E7706E');
        $this->addSql('DROP INDEX IDX_BBC83DB6B1E7706E ON horaire');
        $this->addSql('ALTER TABLE horaire ADD lundi VARCHAR(64) NOT NULL, ADD mardi VARCHAR(64) NOT NULL, ADD mercredi VARCHAR(64) NOT NULL, ADD jeudi VARCHAR(64) NOT NULL, ADD vendredi VARCHAR(64) NOT NULL, ADD samedi VARCHAR(64) NOT NULL, ADD dimanche VARCHAR(64) NOT NULL, DROP jour, DROP ouverture_midi, DROP fermeture_midi, DROP ouverture_soir, DROP fermeture_soir, DROP ferme, DROP restaurant_id');
    }
}
