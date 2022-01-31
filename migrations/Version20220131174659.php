<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220131174659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resa_coaching (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, coaching_id INT NOT NULL, resa_confirm TINYINT(1) NOT NULL, prix DOUBLE PRECISION NOT NULL, nb_personne INT NOT NULL, INDEX IDX_3EBF96E9A76ED395 (user_id), INDEX IDX_3EBF96E919706A33 (coaching_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resa_coaching ADD CONSTRAINT FK_3EBF96E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resa_coaching ADD CONSTRAINT FK_3EBF96E919706A33 FOREIGN KEY (coaching_id) REFERENCES coaching_tarif (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE resa_coaching');
    }
}
