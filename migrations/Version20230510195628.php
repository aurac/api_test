<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510195628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bar (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE foo ADD bar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE foo ADD CONSTRAINT FK_8C73652189A253A FOREIGN KEY (bar_id) REFERENCES bar (id)');
        $this->addSql('CREATE INDEX IDX_8C73652189A253A ON foo (bar_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE foo DROP FOREIGN KEY FK_8C73652189A253A');
        $this->addSql('DROP TABLE bar');
        $this->addSql('DROP INDEX IDX_8C73652189A253A ON foo');
        $this->addSql('ALTER TABLE foo DROP bar_id');
    }
}
