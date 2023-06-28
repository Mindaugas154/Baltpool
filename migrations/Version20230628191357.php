<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628191357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE link_check_history (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, date_add DATETIME NOT NULL, keyword_occurrences VARCHAR(255) NOT NULL, keywords VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE link_check_history_redirects (id INT AUTO_INCREMENT NOT NULL, id_link_history_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, times INT NOT NULL, INDEX IDX_44311E3EA0D1872F (id_link_history_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE link_check_history_redirects ADD CONSTRAINT FK_44311E3EA0D1872F FOREIGN KEY (id_link_history_id) REFERENCES link_check_history (id) ON DELETE CASCADE ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE link_check_history_redirects DROP FOREIGN KEY FK_44311E3EA0D1872F');
        $this->addSql('DROP TABLE link_check_history');
        $this->addSql('DROP TABLE link_check_history_redirects');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
