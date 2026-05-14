<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260514102508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // 1. Add the column allowing NULL values first
        $this->addSql('ALTER TABLE conference ADD slug VARCHAR(255) DEFAULT NULL');

        // 2. Generate a dummy slug for existing records (e.g., "paris-2026")
        $this->addSql("UPDATE conference SET slug = CONCAT(LOWER(city), '-', year)");

        // 3. Enforce the NOT NULL constraint now that there are no NULLs left
        $this->addSql('ALTER TABLE conference ALTER COLUMN slug SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference DROP slug');
    }
}
