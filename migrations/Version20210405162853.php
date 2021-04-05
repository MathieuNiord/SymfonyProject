<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405162853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_utilisateurs AS SELECT pk, identifiant, motdepasse, nom, prenom, aniversaire, isadmin FROM im2021_utilisateurs');
        $this->addSql('DROP TABLE im2021_utilisateurs');
        $this->addSql('CREATE TABLE im2021_utilisateurs --Table des utilisateurs du site
        (pk INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, identifiant VARCHAR(30) NOT NULL COLLATE BINARY --sert de login (doit être unique)
        , motdepasse VARCHAR(64) NOT NULL COLLATE BINARY --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , nom VARCHAR(30) DEFAULT NULL COLLATE BINARY, prenom VARCHAR(30) DEFAULT NULL COLLATE BINARY, isadmin BOOLEAN DEFAULT \'0\' NOT NULL --type booléen
        , anniversaire DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO im2021_utilisateurs (pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin) SELECT pk, identifiant, motdepasse, nom, prenom, aniversaire, isadmin FROM __temp__im2021_utilisateurs');
        $this->addSql('DROP TABLE __temp__im2021_utilisateurs');
        $this->addSql('DROP INDEX IDX_24CC0DF2FB88E14F');
        $this->addSql('DROP INDEX IDX_24CC0DF2F347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier AS SELECT id, utilisateur_id, produit_id, quantite FROM panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, quantite INTEGER NOT NULL, CONSTRAINT FK_24CC0DF2FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES im2021_utilisateurs (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_24CC0DF2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO panier (id, utilisateur_id, produit_id, quantite) SELECT id, utilisateur_id, produit_id, quantite FROM __temp__panier');
        $this->addSql('DROP TABLE __temp__panier');
        $this->addSql('CREATE INDEX IDX_24CC0DF2FB88E14F ON panier (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF2F347EFB ON panier (produit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_utilisateurs AS SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM im2021_utilisateurs');
        $this->addSql('DROP TABLE im2021_utilisateurs');
        $this->addSql('CREATE TABLE im2021_utilisateurs --Table des utilisateurs du site
        (pk INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, identifiant VARCHAR(30) NOT NULL --sert de login (doit être unique)
        , motdepasse VARCHAR(64) NOT NULL --mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer
        , nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, isadmin BOOLEAN DEFAULT \'0\' NOT NULL --type booléen
        , aniversaire DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO im2021_utilisateurs (pk, identifiant, motdepasse, nom, prenom, aniversaire, isadmin) SELECT pk, identifiant, motdepasse, nom, prenom, anniversaire, isadmin FROM __temp__im2021_utilisateurs');
        $this->addSql('DROP TABLE __temp__im2021_utilisateurs');
        $this->addSql('DROP INDEX IDX_24CC0DF2FB88E14F');
        $this->addSql('DROP INDEX IDX_24CC0DF2F347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier AS SELECT id, utilisateur_id, produit_id, quantite FROM panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, quantite INTEGER NOT NULL)');
        $this->addSql('INSERT INTO panier (id, utilisateur_id, produit_id, quantite) SELECT id, utilisateur_id, produit_id, quantite FROM __temp__panier');
        $this->addSql('DROP TABLE __temp__panier');
        $this->addSql('CREATE INDEX IDX_24CC0DF2FB88E14F ON panier (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF2F347EFB ON panier (produit_id)');
    }
}
