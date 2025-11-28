<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128220438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boisson (id_boisson INT AUTO_INCREMENT NOT NULL, lib_boisson VARCHAR(64) NOT NULL, prix_boisson DOUBLE PRECISION NOT NULL, visible TINYINT(1) NOT NULL, alcoolise TINYINT(1) NOT NULL, description_boisson VARCHAR(1024) NOT NULL, PRIMARY KEY (id_boisson)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commande (id_commande INT AUTO_INCREMENT NOT NULL, prix_commande DOUBLE PRECISION NOT NULL, date_commande DATE NOT NULL, PRIMARY KEY (id_commande)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE horaire (id_horaire INT AUTO_INCREMENT NOT NULL, lundi VARCHAR(64) NOT NULL, mardi VARCHAR(64) NOT NULL, mercredi VARCHAR(64) NOT NULL, jeudi VARCHAR(64) NOT NULL, vendredi VARCHAR(64) NOT NULL, samedi VARCHAR(64) NOT NULL, dimanche VARCHAR(64) NOT NULL, PRIMARY KEY (id_horaire)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE menu (id_menu INT AUTO_INCREMENT NOT NULL, lib_menu VARCHAR(64) NOT NULL, prix_menu DOUBLE PRECISION NOT NULL, visible TINYINT(1) NOT NULL, PRIMARY KEY (id_menu)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE personne (id_pers INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(25) DEFAULT NULL, nom VARCHAR(64) DEFAULT NULL, telephone VARCHAR(10) DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, motdepasse VARCHAR(1024) DEFAULT NULL, type VARCHAR(255) NOT NULL, salaire DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY (id_pers)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE plat (id_plat INT AUTO_INCREMENT NOT NULL, lib_plat VARCHAR(64) NOT NULL, prix_plat DOUBLE PRECISION NOT NULL, visible TINYINT(1) DEFAULT NULL, description_plat VARCHAR(1024) NOT NULL, PRIMARY KEY (id_plat)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reservation (id_reservation INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, nb_pers INT NOT NULL, PRIMARY KEY (id_reservation)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE restaurant (id_restau INT AUTO_INCREMENT NOT NULL, lib_restau VARCHAR(64) NOT NULL, adr_restau VARCHAR(100) DEFAULT NULL, cp_restau INT DEFAULT NULL, ville_restau VARCHAR(50) DEFAULT NULL, nb_table INT NOT NULL, nb_etoiles INT DEFAULT NULL, PRIMARY KEY (id_restau)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `table` (id_table INT AUTO_INCREMENT NOT NULL, nb_place INT NOT NULL, disponible TINYINT(1) DEFAULT NULL, PRIMARY KEY (id_table)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_plat (id_type_plat INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, PRIMARY KEY (id_type_plat)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE boisson');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE horaire');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE plat');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE `table`');
        $this->addSql('DROP TABLE type_plat');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
