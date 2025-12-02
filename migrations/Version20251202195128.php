<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251202195128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit ADD id_restau_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27CE27A15 FOREIGN KEY (id_restau_id) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27CE27A15 ON produit (id_restau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27CE27A15');
        $this->addSql('DROP INDEX IDX_29A5EC27CE27A15 ON produit');
        $this->addSql('ALTER TABLE produit DROP id_restau_id');
    }
}
