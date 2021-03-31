<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331093607 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im2021_paniers (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, quantite INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_662878BFFB88E14F ON im2021_paniers (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_662878BFF347EFB ON im2021_paniers (produit_id)');
        $this->addSql('CREATE TABLE im2021_produits (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libelle VARCHAR(30) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE im2021_utilisateurs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, identifiant VARCHAR(30) NOT NULL --sert de login (doit être unique)
        , mot_de_passe VARCHAR(64) NOT NULL --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, anniversaire DATE DEFAULT NULL, est_admin BOOLEAN DEFAULT \'0\' NOT NULL --type booléen
        , paniers VARCHAR(255) DEFAULT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE im2021_paniers');
        $this->addSql('DROP TABLE im2021_produits');
        $this->addSql('DROP TABLE im2021_utilisateurs');
    }
}
