<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002144418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE add_to_favorite DROP FOREIGN KEY FK_F7D949F0A76ED395');
        $this->addSql('ALTER TABLE add_to_favorite DROP FOREIGN KEY FK_F7D949F059D8A214');
        $this->addSql('DROP TABLE add_to_favorite');
        $this->addSql('ALTER TABLE ingredient ADD id_ingredient VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE add_to_favorite (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, recipe_id INT DEFAULT NULL, is_favorite TINYINT(1) DEFAULT NULL, INDEX IDX_F7D949F0A76ED395 (user_id), INDEX IDX_F7D949F059D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE add_to_favorite ADD CONSTRAINT FK_F7D949F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE add_to_favorite ADD CONSTRAINT FK_F7D949F059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE ingredient DROP id_ingredient');
    }
}
