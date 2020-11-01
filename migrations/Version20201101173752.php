<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201101173752 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE secret_organization_unit (secret_id INT NOT NULL, organization_unit_id INT NOT NULL, INDEX IDX_AC3AE65C67176C07 (secret_id), INDEX IDX_AC3AE65C356FF84E (organization_unit_id), PRIMARY KEY(secret_id, organization_unit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE secret_organization_unit ADD CONSTRAINT FK_AC3AE65C67176C07 FOREIGN KEY (secret_id) REFERENCES secret (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE secret_organization_unit ADD CONSTRAINT FK_AC3AE65C356FF84E FOREIGN KEY (organization_unit_id) REFERENCES organization_unit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE secret DROP FOREIGN KEY FK_5CA2E8E5356FF84E');
        $this->addSql('DROP INDEX IDX_5CA2E8E5356FF84E ON secret');
        $this->addSql('ALTER TABLE secret DROP organization_unit_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE secret_organization_unit');
        $this->addSql('ALTER TABLE secret ADD organization_unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE secret ADD CONSTRAINT FK_5CA2E8E5356FF84E FOREIGN KEY (organization_unit_id) REFERENCES organization_unit (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5CA2E8E5356FF84E ON secret (organization_unit_id)');
    }
}
