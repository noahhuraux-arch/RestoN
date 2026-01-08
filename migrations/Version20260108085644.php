<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260108085644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY `FK_29A5EC27CE27A15`');
        $this->addSql('DROP INDEX IDX_29A5EC27CE27A15 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE id_restau_id restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27B1E7706E ON produit (restaurant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27B1E7706E');
        $this->addSql('DROP INDEX IDX_29A5EC27B1E7706E ON produit');
        $this->addSql('ALTER TABLE produit CHANGE restaurant_id id_restau_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT `FK_29A5EC27CE27A15` FOREIGN KEY (id_restau_id) REFERENCES restaurant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_29A5EC27CE27A15 ON produit (id_restau_id)');
    }
}
