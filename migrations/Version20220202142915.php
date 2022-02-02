<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202142915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resa_coaching ADD evenement_id INT DEFAULT NULL, ADD date_resa DATETIME NOT NULL, CHANGE coaching_id coaching_id INT NOT NULL, CHANGE nb_personne nb_personne INT NOT NULL');
        $this->addSql('ALTER TABLE resa_coaching ADD CONSTRAINT FK_3EBF96E9FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_3EBF96E9FD02F13 ON resa_coaching (evenement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resa_coaching DROP FOREIGN KEY FK_3EBF96E9FD02F13');
        $this->addSql('DROP INDEX IDX_3EBF96E9FD02F13 ON resa_coaching');
        $this->addSql('ALTER TABLE resa_coaching DROP evenement_id, DROP date_resa, CHANGE coaching_id coaching_id INT DEFAULT NULL, CHANGE nb_personne nb_personne INT DEFAULT NULL');
    }
}
