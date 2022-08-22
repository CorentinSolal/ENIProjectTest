<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220822130325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F29D1C3019');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F228E1A3BC');
        $this->addSql('DROP INDEX IDX_3C3FD3F29D1C3019 ON sortie');
        $this->addSql('DROP INDEX IDX_3C3FD3F228E1A3BC ON sortie');
        $this->addSql('ALTER TABLE sortie DROP participant_id, DROP organisteur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie ADD participant_id INT DEFAULT NULL, ADD organisteur_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F29D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F228E1A3BC FOREIGN KEY (organisteur_id) REFERENCES participant (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F29D1C3019 ON sortie (participant_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F228E1A3BC ON sortie (organisteur_id)');
    }
}
