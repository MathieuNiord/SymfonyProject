<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330102725 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im2021_paniers (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_utilisateur VARCHAR(255) NOT NULL, quantite INTEGER NOT NULL, id_produit VARCHAR(255) NOT NULL)');
        $this->addSql('DROP TABLE panier');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_utilisateur VARCHAR(255) NOT NULL COLLATE BINARY, quantite INTEGER NOT NULL, id_produit VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('DROP TABLE im2021_paniers');
    }
}
