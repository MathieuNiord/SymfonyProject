<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330174358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_paniers AS SELECT id, quantite FROM im2021_paniers');
        $this->addSql('DROP TABLE im2021_paniers');
        $this->addSql('CREATE TABLE im2021_paniers (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, quantite INTEGER NOT NULL, CONSTRAINT FK_662878BFFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES im2021_utilisateurs (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_662878BFF347EFB FOREIGN KEY (produit_id) REFERENCES im2021_produits (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO im2021_paniers (id, quantite) SELECT id, quantite FROM __temp__im2021_paniers');
        $this->addSql('DROP TABLE __temp__im2021_paniers');
        $this->addSql('CREATE INDEX IDX_662878BFFB88E14F ON im2021_paniers (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_662878BFF347EFB ON im2021_paniers (produit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_662878BFFB88E14F');
        $this->addSql('DROP INDEX IDX_662878BFF347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im2021_paniers AS SELECT id, quantite FROM im2021_paniers');
        $this->addSql('DROP TABLE im2021_paniers');
        $this->addSql('CREATE TABLE im2021_paniers (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quantite INTEGER NOT NULL, id_utilisateur VARCHAR(255) NOT NULL COLLATE BINARY, id_produit VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO im2021_paniers (id, quantite) SELECT id, quantite FROM __temp__im2021_paniers');
        $this->addSql('DROP TABLE __temp__im2021_paniers');
    }
}