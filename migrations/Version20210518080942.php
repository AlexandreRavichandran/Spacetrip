<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518080942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create a destination field in trip table, and possibleDestination field in spacecraft table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE destination (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, distance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spacecraft_destination (spacecraft_id INT NOT NULL, destination_id INT NOT NULL, INDEX IDX_45B27F141C6AF6FD (spacecraft_id), INDEX IDX_45B27F14816C6140 (destination_id), PRIMARY KEY(spacecraft_id, destination_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spacecraft_destination ADD CONSTRAINT FK_45B27F141C6AF6FD FOREIGN KEY (spacecraft_id) REFERENCES spacecraft (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE spacecraft_destination ADD CONSTRAINT FK_45B27F14816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trip ADD destination_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id)');
        $this->addSql('CREATE INDEX IDX_7656F53B816C6140 ON trip (destination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spacecraft_destination DROP FOREIGN KEY FK_45B27F14816C6140');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B816C6140');
        $this->addSql('DROP TABLE destination');
        $this->addSql('DROP TABLE spacecraft_destination');
        $this->addSql('DROP INDEX IDX_7656F53B816C6140 ON trip');
        $this->addSql('ALTER TABLE trip DROP destination_id');
    }
}
