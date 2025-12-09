<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251209143114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create PermissionsMobileApp and UserMobileApp tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE permissions_mobile_app (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8A1319B45E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_mobile_app (id INT AUTO_INCREMENT NOT NULL, permission_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_42C123405E237E06 (name), UNIQUE INDEX UNIQ_42C12340E7927C74 (email), INDEX IDX_42C12340FED90CCA (permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_mobile_app ADD CONSTRAINT FK_42C12340FED90CCA FOREIGN KEY (permission_id) REFERENCES permissions_mobile_app (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user_mobile_app DROP FOREIGN KEY FK_42C12340FED90CCA');
        $this->addSql('DROP TABLE permissions_mobile_app');
        $this->addSql('DROP TABLE user_mobile_app');
    }
}
