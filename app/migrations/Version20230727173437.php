<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727173437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE expense_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE income_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tracker_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories (id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3AF34668A76ED395 ON categories (user_id)');
        $this->addSql('CREATE TABLE expense (id INT NOT NULL, user_id INT DEFAULT NULL, category_id INT DEFAULT NULL, amount NUMERIC(17, 5) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D3A8DA6A76ED395 ON expense (user_id)');
        $this->addSql('CREATE INDEX IDX_2D3A8DA612469DE2 ON expense (category_id)');
        $this->addSql('CREATE TABLE income (id INT NOT NULL, user_id INT DEFAULT NULL, amount NUMERIC(17, 5) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3FA862D0A76ED395 ON income (user_id)');
        $this->addSql('CREATE TABLE tracker_user (id INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, balance NUMERIC(17, 5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668A76ED395 FOREIGN KEY (user_id) REFERENCES tracker_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6A76ED395 FOREIGN KEY (user_id) REFERENCES tracker_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA612469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE income ADD CONSTRAINT FK_3FA862D0A76ED395 FOREIGN KEY (user_id) REFERENCES tracker_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE categories_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE expense_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE income_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tracker_user_id_seq CASCADE');
        $this->addSql('ALTER TABLE categories DROP CONSTRAINT FK_3AF34668A76ED395');
        $this->addSql('ALTER TABLE expense DROP CONSTRAINT FK_2D3A8DA6A76ED395');
        $this->addSql('ALTER TABLE expense DROP CONSTRAINT FK_2D3A8DA612469DE2');
        $this->addSql('ALTER TABLE income DROP CONSTRAINT FK_3FA862D0A76ED395');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE expense');
        $this->addSql('DROP TABLE income');
        $this->addSql('DROP TABLE tracker_user');
    }
}
