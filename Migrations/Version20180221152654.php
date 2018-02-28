<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180221152654 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mi_comments_bundle_comment (id INT AUTO_INCREMENT NOT NULL, entityId VARCHAR(255) NOT NULL, entityTitle LONGTEXT NOT NULL, userName LONGTEXT NOT NULL, userEmail LONGTEXT NOT NULL, comment LONGTEXT NOT NULL, dateCreated DATETIME NOT NULL, datePublished DATETIME DEFAULT NULL, INDEX entityId (entityId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mi_comments_bundle_comment');
    }
}
