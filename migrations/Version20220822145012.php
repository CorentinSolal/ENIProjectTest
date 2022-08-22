<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220822145012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant_sortie DROP FOREIGN KEY FK_8E436D739D1C3019');
        $this->addSql('ALTER TABLE participant_sortie DROP FOREIGN KEY FK_8E436D73CC72D953');
        $this->addSql('DROP TABLE participant_sortie');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11AF5D55E1');
        $this->addSql('DROP INDEX IDX_D79F6B11AF5D55E1 ON participant');
        $this->addSql('ALTER TABLE participant ADD roles JSON NOT NULL, DROP campus_id, CHANGE mail mail VARCHAR(180) NOT NULL, CHANGE mot_passe password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B115126AC48 ON participant (mail)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant_sortie (participant_id INT NOT NULL, sortie_id INT NOT NULL, INDEX IDX_8E436D73CC72D953 (sortie_id), INDEX IDX_8E436D739D1C3019 (participant_id), PRIMARY KEY(participant_id, sortie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE participant_sortie ADD CONSTRAINT FK_8E436D739D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant_sortie ADD CONSTRAINT FK_8E436D73CC72D953 FOREIGN KEY (sortie_id) REFERENCES sortie (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_D79F6B115126AC48 ON participant');
        $this->addSql('ALTER TABLE participant ADD campus_id INT NOT NULL, DROP roles, CHANGE mail mail VARCHAR(255) NOT NULL, CHANGE password mot_passe VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_D79F6B11AF5D55E1 ON participant (campus_id)');
    }
}
