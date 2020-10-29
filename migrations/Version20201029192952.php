<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201029192952 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization_unit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization_unit_user (organization_unit_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3962ED15356FF84E (organization_unit_id), INDEX IDX_3962ED15A76ED395 (user_id), PRIMARY KEY(organization_unit_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE secret (id INT AUTO_INCREMENT NOT NULL, organization_unit_id INT NOT NULL, site VARCHAR(255) NOT NULL, account VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_5CA2E8E5356FF84E (organization_unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', is_admin TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organization_unit_user ADD CONSTRAINT FK_3962ED15356FF84E FOREIGN KEY (organization_unit_id) REFERENCES organization_unit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organization_unit_user ADD CONSTRAINT FK_3962ED15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE secret ADD CONSTRAINT FK_5CA2E8E5356FF84E FOREIGN KEY (organization_unit_id) REFERENCES organization_unit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_unit_user DROP FOREIGN KEY FK_3962ED15356FF84E');
        $this->addSql('ALTER TABLE secret DROP FOREIGN KEY FK_5CA2E8E5356FF84E');
        $this->addSql('ALTER TABLE organization_unit_user DROP FOREIGN KEY FK_3962ED15A76ED395');
        $this->addSql('DROP TABLE organization_unit');
        $this->addSql('DROP TABLE organization_unit_user');
        $this->addSql('DROP TABLE secret');
        $this->addSql('DROP TABLE user');
    }
}
