<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251121120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add price column to workshop_information_material';
    }

    public function up(Schema $schema): void
    {
        // Add price column with default 0.000000
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_information_material
            ADD COLUMN price NUMERIC(20, 6) NOT NULL DEFAULT '0.000000'
        SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE workshop_information_material DROP COLUMN price
        SQL
        );
    }
}

