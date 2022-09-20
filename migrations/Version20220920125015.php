<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920125015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE location_vehicule');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBDC2902E0');
        $this->addSql('DROP INDEX IDX_5E9E89CBDC2902E0 ON location');
        $this->addSql('ALTER TABLE location DROP client_id_id, CHANGE statut statut LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location_vehicule (location_id INT NOT NULL, vehicule_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_F87ADDFF64D218E (location_id), INDEX IDX_F87ADDFF4A4A3511 (vehicule_id), PRIMARY KEY(location_id, vehicule_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE location_vehicule ADD CONSTRAINT FK_F87ADDFF64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_vehicule ADD CONSTRAINT FK_F87ADDFF4A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location ADD client_id_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE statut statut VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBDC2902E0 FOREIGN KEY (client_id_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CBDC2902E0 ON location (client_id_id)');
    }
}
