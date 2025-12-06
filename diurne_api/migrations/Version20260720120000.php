<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260720120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create workshop_information_material table to store materials linked to workshop information';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE workshop_information_material (id INT AUTO_INCREMENT NOT NULL, material_id INT NOT NULL, workshop_information_id INT NOT NULL, rate NUMERIC(20, 6) NOT NULL, INDEX IDX_workshop_information_material_material_id (material_id), INDEX IDX_workshop_information_material_workshop_information_id (workshop_information_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE workshop_information_material ADD CONSTRAINT FK_workshop_information_material_material FOREIGN KEY (material_id) REFERENCES material (id)');
        $this->addSql('ALTER TABLE workshop_information_material ADD CONSTRAINT FK_workshop_information_material_workshop_information FOREIGN KEY (workshop_information_id) REFERENCES workshop_information (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE workshop_information_material');
    }
}
