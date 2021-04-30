<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430075847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add created at and rating fields to feedback table, created at and updated at to user table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback ADD created_at DATETIME NOT NULL, ADD rating INT NOT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE spacecraft_id spacecraft_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP created_at, DROP rating, CHANGE user_id user_id INT NOT NULL, CHANGE spacecraft_id spacecraft_id INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP created_at, DROP updated_at');
    }
}
