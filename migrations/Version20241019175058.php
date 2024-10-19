<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019175058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materia DROP FOREIGN KEY FK_6DF052846D5BDFE1');
        $this->addSql('DROP INDEX IDX_6DF052846D5BDFE1 ON materia');
        $this->addSql('ALTER TABLE materia DROP tarea_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE materia ADD tarea_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materia ADD CONSTRAINT FK_6DF052846D5BDFE1 FOREIGN KEY (tarea_id) REFERENCES tarea (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6DF052846D5BDFE1 ON materia (tarea_id)');
    }
}
