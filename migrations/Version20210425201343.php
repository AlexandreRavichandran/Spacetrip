<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210425201343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Spacecraft table and its field, and OneToMany relation with Trip table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE spacecraft (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, possible_destination VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, number_of_seat INT NOT NULL, nationality VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, speed DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, rating DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trip ADD spacecraft_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B1C6AF6FD FOREIGN KEY (spacecraft_id) REFERENCES spacecraft (id)');
        $this->addSql('CREATE INDEX IDX_7656F53B1C6AF6FD ON trip (spacecraft_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B1C6AF6FD');
        $this->addSql('DROP TABLE spacecraft');
        $this->addSql('DROP INDEX IDX_7656F53B1C6AF6FD ON trip');
        $this->addSql('ALTER TABLE trip DROP spacecraft_id');
    }
}
