<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110173731 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE user (id BIGINT AUTO_INCREMENT NOT NULL, client_id VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, oauth_type VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, plain_password VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json_array)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BIGINT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, body LONGTEXT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_885DBAFAA76ED395 (user_id), INDEX IDX_885DBAFA7B00651C (status), INDEX IDX_885DBAFA8B8E8428 (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');        
	    $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, post_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', user_id BIGINT DEFAULT NULL, comment LONGTEXT NOT NULL, created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', updated_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_5F9E962A4B89032C (post_id), INDEX IDX_5F9E962AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A4B89032C');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAA76ED395');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE user');
    }
}
