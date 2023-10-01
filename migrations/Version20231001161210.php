<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231001161210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projects_technology (projects_id INT NOT NULL, technology_id INT NOT NULL, INDEX IDX_42DCC3B71EDE0F55 (projects_id), INDEX IDX_42DCC3B74235D463 (technology_id), PRIMARY KEY(projects_id, technology_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technology (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projects_technology ADD CONSTRAINT FK_42DCC3B71EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_technology ADD CONSTRAINT FK_42DCC3B74235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects_technology DROP FOREIGN KEY FK_42DCC3B71EDE0F55');
        $this->addSql('ALTER TABLE projects_technology DROP FOREIGN KEY FK_42DCC3B74235D463');
        $this->addSql('DROP TABLE projects_technology');
        $this->addSql('DROP TABLE technology');
    }
}
