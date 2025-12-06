<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260704120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create manufacturer_price table and backfill data from existing manufacturer_price_grid wool/silk prices';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE manufacturer_price (id INT AUTO_INCREMENT NOT NULL, manufacturer_price_grid_id INT NOT NULL, material_id INT NOT NULL, price NUMERIC(20, 6) NOT NULL, effective_date DATE NOT NULL, INDEX IDX_A63BDCE9B49E5455 (manufacturer_price_grid_id), INDEX IDX_A63BDCE995DA4672 (material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE manufacturer_price ADD CONSTRAINT FK_A63BDCE9B49E5455 FOREIGN KEY (manufacturer_price_grid_id) REFERENCES manufacturer_price_grid (id)');
        $this->addSql('ALTER TABLE manufacturer_price ADD CONSTRAINT FK_A63BDCE995DA4672 FOREIGN KEY (material_id) REFERENCES material (id)');

        $this->addSql('INSERT INTO manufacturer_price (manufacturer_price_grid_id, material_id, price, effective_date) SELECT mpg.id, m.id, mpg.wool_price, STR_TO_DATE(CONCAT(mpg.year, "-01-01"), "%Y-%m-%d") FROM manufacturer_price_grid mpg INNER JOIN material m ON m.reference = "Wool" WHERE mpg.wool_price IS NOT NULL');
        $this->addSql('INSERT INTO manufacturer_price (manufacturer_price_grid_id, material_id, price, effective_date) SELECT mpg.id, m.id, mpg.silk_price, STR_TO_DATE(CONCAT(mpg.year, "-01-01"), "%Y-%m-%d") FROM manufacturer_price_grid mpg INNER JOIN material m ON m.reference = "Silk" WHERE mpg.silk_price IS NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE manufacturer_price DROP FOREIGN KEY FK_A63BDCE995DA4672');
        $this->addSql('ALTER TABLE manufacturer_price DROP FOREIGN KEY FK_A63BDCE9B49E5455');
        $this->addSql('DROP TABLE manufacturer_price');
    }
}
