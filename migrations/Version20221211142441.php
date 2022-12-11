<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221211142441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chaton DROP FOREIGN KEY FK_EED8B068A3C7387');
        $this->addSql('CREATE TABLE cateforie (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP INDEX IDX_EED8B068A3C7387 ON chaton');
        $this->addSql('ALTER TABLE chaton CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE categorie_id_id categorie_id INT NOT NULL, CHANGE sterilise sterelise TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE chaton ADD CONSTRAINT FK_EED8B06BCF5E72D FOREIGN KEY (categorie_id) REFERENCES cateforie (id)');
        $this->addSql('CREATE INDEX IDX_EED8B06BCF5E72D ON chaton (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chaton DROP FOREIGN KEY FK_EED8B06BCF5E72D');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE cateforie');
        $this->addSql('DROP INDEX IDX_EED8B06BCF5E72D ON chaton');
        $this->addSql('ALTER TABLE chaton CHANGE photo photo VARCHAR(255) NOT NULL, CHANGE categorie_id categorie_id_id INT NOT NULL, CHANGE sterelise sterilise TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE chaton ADD CONSTRAINT FK_EED8B068A3C7387 FOREIGN KEY (categorie_id_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_EED8B068A3C7387 ON chaton (categorie_id_id)');
    }
}
