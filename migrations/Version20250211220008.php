<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211220008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription ADD etat VARCHAR(20) NOT NULL, ADD etudiant_id INT NOT NULL, ADD activity_id INT NOT NULL, DROP participant');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D6DDEAB1A3 ON inscription (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D681C06096 ON inscription (activity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6DDEAB1A3');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D681C06096');
        $this->addSql('DROP INDEX IDX_5E90F6D6DDEAB1A3 ON inscription');
        $this->addSql('DROP INDEX IDX_5E90F6D681C06096 ON inscription');
        $this->addSql('ALTER TABLE inscription ADD participant VARCHAR(255) NOT NULL, DROP etat, DROP etudiant_id, DROP activity_id');
    }
}
