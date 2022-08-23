<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823132158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F228E1A3BC');
        $this->addSql('DROP INDEX IDX_3C3FD3F228E1A3BC ON sortie');
        $this->addSql('ALTER TABLE sortie CHANGE date_heure_debut date_heure_debut DATETIME NOT NULL, CHANGE organisteur_id organisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2D936B2FA FOREIGN KEY (organisateur_id) REFERENCES participant (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D936B2FA ON sortie (organisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2D936B2FA');
        $this->addSql('DROP INDEX IDX_3C3FD3F2D936B2FA ON sortie');
        $this->addSql('ALTER TABLE sortie CHANGE date_heure_debut date_heure_debut DATE NOT NULL, CHANGE organisateur_id organisteur_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F228E1A3BC FOREIGN KEY (organisteur_id) REFERENCES participant (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F228E1A3BC ON sortie (organisteur_id)');
    }
}
