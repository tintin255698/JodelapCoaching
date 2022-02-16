<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214170422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE resa_coaching CHANGE nb_personne nb_personne INT NOT NULL, CHANGE quantity_event quantity_event INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP updated_at');
        $this->addSql('ALTER TABLE resa_coaching CHANGE nb_personne nb_personne INT DEFAULT NULL, CHANGE quantity_event quantity_event INT DEFAULT NULL');
    }
}
