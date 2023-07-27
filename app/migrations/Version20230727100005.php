<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727100005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE income_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE income (id INT NOT NULL, user_id INT DEFAULT NULL, amount NUMERIC(17, 5) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3FA862D0A76ED395 ON income (user_id)');
        $this->addSql('ALTER TABLE income ADD CONSTRAINT FK_3FA862D0A76ED395 FOREIGN KEY (user_id) REFERENCES tracker_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expense ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE expense ALTER amount TYPE NUMERIC(17, 5)');
        $this->addSql('ALTER TABLE tracker_user ALTER balance TYPE NUMERIC(17, 5)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE income_id_seq CASCADE');
        $this->addSql('ALTER TABLE income DROP CONSTRAINT FK_3FA862D0A76ED395');
        $this->addSql('DROP TABLE income');
        $this->addSql('ALTER TABLE tracker_user ALTER balance TYPE NUMERIC(7, 3)');
        $this->addSql('ALTER TABLE expense ALTER amount TYPE NUMERIC(7, 3)');
        $this->addSql('ALTER TABLE expense ALTER description SET NOT NULL');
    }
}
