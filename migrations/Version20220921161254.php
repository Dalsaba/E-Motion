<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220921161254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location ADD client_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE statut statut VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CBDC2902E0 ON location (client_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBDC2902E0');
        $this->addSql('DROP INDEX IDX_5E9E89CBDC2902E0 ON location');
        $this->addSql('ALTER TABLE location DROP client_id_id, CHANGE statut statut LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
