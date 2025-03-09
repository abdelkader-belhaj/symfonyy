<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211220627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD image_file_name VARCHAR(255) DEFAULT NULL, DROP enseignant, DROP activity, CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE inscription ADD etat VARCHAR(20) NOT NULL, ADD etudiant_id INT NOT NULL, ADD activity_id INT NOT NULL, DROP participant');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D6DDEAB1A3 ON inscription (etudiant_id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D681C06096 ON inscription (activity_id)');
        $this->addSql('ALTER TABLE user ADD email VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, ADD lastname VARCHAR(100) NOT NULL, ADD firstname VARCHAR(100) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD zipcode VARCHAR(5) NOT NULL, ADD city VARCHAR(150) NOT NULL, ADD image_file_name VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL, CHANGE nom password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD enseignant VARCHAR(255) NOT NULL, ADD activity VARCHAR(255) NOT NULL, DROP image_file_name, CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6DDEAB1A3');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D681C06096');
        $this->addSql('DROP INDEX IDX_5E90F6D6DDEAB1A3 ON inscription');
        $this->addSql('DROP INDEX IDX_5E90F6D681C06096 ON inscription');
        $this->addSql('ALTER TABLE inscription ADD participant VARCHAR(255) NOT NULL, DROP etat, DROP etudiant_id, DROP activity_id');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) NOT NULL, DROP email, DROP roles, DROP password, DROP lastname, DROP firstname, DROP address, DROP zipcode, DROP city, DROP image_file_name, DROP created_at');
    }
}
