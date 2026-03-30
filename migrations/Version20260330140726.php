<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260330140726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_quantite (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, produit_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_5DD252E3F347EFB (produit_id), INDEX IDX_5DD252E382EA2E54 (commande_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE commande_quantite ADD CONSTRAINT FK_5DD252E3F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commande_quantite ADD CONSTRAINT FK_5DD252E382EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY `FK_DF1E9E8782EA2E54`');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY `FK_DF1E9E87F347EFB`');
        $this->addSql('DROP TABLE commande_produit');
        $this->addSql('ALTER TABLE personne CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_produit (commande_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_DF1E9E87F347EFB (produit_id), INDEX IDX_DF1E9E8782EA2E54 (commande_id), PRIMARY KEY (commande_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT `FK_DF1E9E8782EA2E54` FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT `FK_DF1E9E87F347EFB` FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_quantite DROP FOREIGN KEY FK_5DD252E3F347EFB');
        $this->addSql('ALTER TABLE commande_quantite DROP FOREIGN KEY FK_5DD252E382EA2E54');
        $this->addSql('DROP TABLE commande_quantite');
        $this->addSql('ALTER TABLE personne CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
