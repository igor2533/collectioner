<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210809093238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE likes likes VARCHAR(255) NOT NULL, CHANGE date_created date_created DATETIME NOT NULL, CHANGE date_modife date_modife DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE likes likes VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'0\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_created date_created DATETIME DEFAULT NULL, CHANGE date_modife date_modife VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
