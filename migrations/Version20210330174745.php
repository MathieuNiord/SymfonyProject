<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330174745 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_662878BFF347EFB');
        $this->addSql('DROP INDEX IDX_662878BFFB88E14F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_paniers AS SELECT id, utilisateur_id, produit_id, quantite FROM im2021_paniers');
        $this->addSql('DROP TABLE im2021_paniers');
        $this->addSql('CREATE TABLE im2021_paniers (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, quantite INTEGER NOT NULL, CONSTRAINT FK_662878BFFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES im2021_utilisateurs (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_662878BFF347EFB FOREIGN KEY (produit_id) REFERENCES im2021_produits (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO im2021_paniers (id, utilisateur_id, produit_id, quantite) SELECT id, utilisateur_id, produit_id, quantite FROM __temp__im2021_paniers');
        $this->addSql('DROP TABLE __temp__im2021_paniers');
        $this->addSql('CREATE INDEX IDX_662878BFF347EFB ON im2021_paniers (produit_id)');
        $this->addSql('CREATE INDEX IDX_662878BFFB88E14F ON im2021_paniers (utilisateur_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_utilisateurs AS SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM im2021_utilisateurs');
        $this->addSql('DROP TABLE im2021_utilisateurs');
        $this->addSql('CREATE TABLE im2021_utilisateurs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, identifiant VARCHAR(30) NOT NULL COLLATE BINARY --sert de login (doit être unique)
        , nom VARCHAR(30) DEFAULT NULL COLLATE BINARY, prenom VARCHAR(30) DEFAULT NULL COLLATE BINARY, anniversaire DATE DEFAULT NULL, mot_de_passe VARCHAR(64) NOT NULL --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , est_admin BOOLEAN DEFAULT \'0\' NOT NULL --type booléen
        )');
        $this->addSql('INSERT INTO im2021_utilisateurs (id, identifiant, mot_de_passe, nom, prenom, anniversaire, est_admin) SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM __temp__im2021_utilisateurs');
        $this->addSql('DROP TABLE __temp__im2021_utilisateurs');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_662878BFFB88E14F');
        $this->addSql('DROP INDEX IDX_662878BFF347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_paniers AS SELECT id, utilisateur_id, produit_id, quantite FROM im2021_paniers');
        $this->addSql('DROP TABLE im2021_paniers');
        $this->addSql('CREATE TABLE im2021_paniers (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, quantite INTEGER NOT NULL)');
        $this->addSql('INSERT INTO im2021_paniers (id, utilisateur_id, produit_id, quantite) SELECT id, utilisateur_id, produit_id, quantite FROM __temp__im2021_paniers');
        $this->addSql('DROP TABLE __temp__im2021_paniers');
        $this->addSql('CREATE INDEX IDX_662878BFFB88E14F ON im2021_paniers (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_662878BFF347EFB ON im2021_paniers (produit_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_utilisateurs AS SELECT id, identifiant, mot_de_passe, nom, prenom, anniversaire, est_admin FROM im2021_utilisateurs');
        $this->addSql('DROP TABLE im2021_utilisateurs');
        $this->addSql('CREATE TABLE im2021_utilisateurs (pk INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, identifiant VARCHAR(30) NOT NULL --sert de login (doit être unique)
        , nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, anniversaire DATE DEFAULT NULL, motdepasse VARCHAR(64) NOT NULL COLLATE BINARY --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , isadmin BOOLEAN DEFAULT \'0\' NOT NULL --type booléen
        )');
        $this->addSql('INSERT INTO im2021_utilisateurs (pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin) SELECT id, identifiant, mot_de_passe, nom, prenom, anniversaire, est_admin FROM __temp__im2021_utilisateurs');
        $this->addSql('DROP TABLE __temp__im2021_utilisateurs');
    }
}
