<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220824133830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location_client (location_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_8C368C5764D218E (location_id), INDEX IDX_8C368C5719EB6921 (client_id), PRIMARY KEY(location_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_vehicule (location_id INT NOT NULL, vehicule_id INT NOT NULL, INDEX IDX_F87ADDFF64D218E (location_id), INDEX IDX_F87ADDFF4A4A3511 (vehicule_id), PRIMARY KEY(location_id, vehicule_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location_client ADD CONSTRAINT FK_8C368C5764D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_client ADD CONSTRAINT FK_8C368C5719EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_vehicule ADD CONSTRAINT FK_F87ADDFF64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_vehicule ADD CONSTRAINT FK_F87ADDFF4A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE location_client');
        $this->addSql('DROP TABLE location_vehicule');
    }
}
