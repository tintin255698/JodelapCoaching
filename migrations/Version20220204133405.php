<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204133405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resa_coaching ADD coffret_produit_id INT DEFAULT NULL, ADD prix_coffret INT DEFAULT NULL, ADD heures_coffret INT DEFAULT NULL, CHANGE nb_personne nb_personne INT NOT NULL');
        $this->addSql('ALTER TABLE resa_coaching ADD CONSTRAINT FK_3EBF96E9ED4C5097 FOREIGN KEY (coffret_produit_id) REFERENCES coffret (id)');
        $this->addSql('CREATE INDEX IDX_3EBF96E9ED4C5097 ON resa_coaching (coffret_produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resa_coaching DROP FOREIGN KEY FK_3EBF96E9ED4C5097');
        $this->addSql('DROP INDEX IDX_3EBF96E9ED4C5097 ON resa_coaching');
        $this->addSql('ALTER TABLE resa_coaching DROP coffret_produit_id, DROP prix_coffret, DROP heures_coffret, CHANGE nb_personne nb_personne INT DEFAULT NULL');
    }
}
