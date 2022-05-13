<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220512153734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
/*         $this->addSql('ALTER TABLE adherent ADD roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
 */        $this->addSql('ALTER TABLE livre CHANGE prix prix DOUBLE PRECISION DEFAULT NULL, CHANGE annee annee INT DEFAULT NULL, CHANGE langue langue VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP roles');
        $this->addSql('ALTER TABLE livre CHANGE prix prix DOUBLE PRECISION NOT NULL, CHANGE annee annee INT NOT NULL, CHANGE langue langue VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
