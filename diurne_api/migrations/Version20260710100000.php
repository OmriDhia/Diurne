<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260710100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace workshop_information.tarif_texture_id with tarif_group_id foreign key';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE workshop_information ADD tarif_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE workshop_information ADD CONSTRAINT FK_workshop_information_tarif_group FOREIGN KEY (tarif_group_id) REFERENCES tarif_group (id)');
        $this->addSql('CREATE INDEX IDX_workshop_information_tarif_group ON workshop_information (tarif_group_id)');
        $this->addSql('ALTER TABLE workshop_information DROP COLUMN tarif_texture_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE workshop_information ADD tarif_texture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE workshop_information DROP FOREIGN KEY FK_workshop_information_tarif_group');
        $this->addSql('DROP INDEX IDX_workshop_information_tarif_group ON workshop_information');
        $this->addSql('ALTER TABLE workshop_information DROP COLUMN tarif_group_id');
    }
}
