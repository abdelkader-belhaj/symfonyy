<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211180921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AE455FCC0');
        $this->addSql('DROP INDEX IDX_AC74095AE455FCC0 ON activity');
        $this->addSql('ALTER TABLE activity DROP enseignant_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD enseignant_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC74095AE455FCC0 ON activity (enseignant_id)');
    }
}
