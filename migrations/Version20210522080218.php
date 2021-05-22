<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210522080218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add reservation price, and price per km fields on the spaccraft table, AND add price field on trip table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spacecraft ADD reservation_price DOUBLE PRECISION NOT NULL, ADD price_per_distance DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE trip ADD price DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spacecraft DROP reservation_price, DROP price_per_distance');
        $this->addSql('ALTER TABLE trip DROP price');
    }
}
