<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002113652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE add_to_favorite ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE add_to_favorite ADD CONSTRAINT FK_F7D949F0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_F7D949F0A76ED395 ON add_to_favorite (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE add_to_favorite DROP FOREIGN KEY FK_F7D949F0A76ED395');
        $this->addSql('DROP INDEX IDX_F7D949F0A76ED395 ON add_to_favorite');
        $this->addSql('ALTER TABLE add_to_favorite DROP user_id');
    }
}
