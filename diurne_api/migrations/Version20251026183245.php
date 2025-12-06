<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251026183245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE manufacturer_price_grid (id INT AUTO_INCREMENT NOT NULL, manufacturer_id INT NOT NULL, quality_id INT NOT NULL, year INT NOT NULL, wool_price NUMERIC(20, 6) DEFAULT NULL, silk_price NUMERIC(20, 6) DEFAULT NULL, tariff_grid VARCHAR(50) DEFAULT NULL, knots INT DEFAULT NULL, special VARCHAR(255) DEFAULT NULL, standard_velours NUMERIC(20, 6) DEFAULT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_8466108DA23B42D (manufacturer_id), INDEX IDX_8466108DBCFC6D57 (quality_id), UNIQUE INDEX unique_manufacturer_quality_year (manufacturer_id, quality_id, year), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE manufacturer_price_grid ADD CONSTRAINT FK_8466108DA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('ALTER TABLE manufacturer_price_grid ADD CONSTRAINT FK_8466108DBCFC6D57 FOREIGN KEY (quality_id) REFERENCES quality (id)');
        $this->addSql('ALTER TABLE order_payment_detail CHANGE distribution distribution NUMERIC(20, 6) DEFAULT \'0\' NOT NULL, CHANGE allocated_amount_ttc allocated_amount_ttc NUMERIC(20, 6) DEFAULT \'0\' NOT NULL, CHANGE remaining_amount_ttc remaining_amount_ttc NUMERIC(20, 6) DEFAULT \'0\' NOT NULL, CHANGE total_amount_ttc total_amount_ttc NUMERIC(20, 6) DEFAULT \'0\' NOT NULL, CHANGE tva tva NUMERIC(20, 6) DEFAULT \'0\' NOT NULL, CHANGE allocated_amount_ht allocated_amount_ht NUMERIC(20, 6) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manufacturer_price_grid DROP FOREIGN KEY FK_8466108DA23B42D');
        $this->addSql('ALTER TABLE manufacturer_price_grid DROP FOREIGN KEY FK_8466108DBCFC6D57');
        $this->addSql('DROP TABLE manufacturer_price_grid');
        $this->addSql('ALTER TABLE order_payment_detail CHANGE distribution distribution NUMERIC(20, 6) DEFAULT \'0.000000\' NOT NULL, CHANGE allocated_amount_ttc allocated_amount_ttc NUMERIC(20, 6) DEFAULT \'0.000000\' NOT NULL, CHANGE remaining_amount_ttc remaining_amount_ttc NUMERIC(20, 6) DEFAULT \'0.000000\' NOT NULL, CHANGE total_amount_ttc total_amount_ttc NUMERIC(20, 6) DEFAULT \'0.000000\' NOT NULL, CHANGE tva tva NUMERIC(20, 6) DEFAULT \'0.000000\' NOT NULL, CHANGE allocated_amount_ht allocated_amount_ht NUMERIC(20, 6) DEFAULT \'0.000000\' NOT NULL');
    }
}
