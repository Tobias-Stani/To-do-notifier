<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023025153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cronometro ADD cronometro_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cronometro ADD CONSTRAINT FK_D249178A80602616 FOREIGN KEY (cronometro_id) REFERENCES materia (id)');
        $this->addSql('CREATE INDEX IDX_D249178A80602616 ON cronometro (cronometro_id)');
        $this->addSql('ALTER TABLE tarea ADD materia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tarea ADD CONSTRAINT FK_3CA05366B54DBBCB FOREIGN KEY (materia_id) REFERENCES materia (id)');
        $this->addSql('CREATE INDEX IDX_3CA05366B54DBBCB ON tarea (materia_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarea DROP FOREIGN KEY FK_3CA05366B54DBBCB');
        $this->addSql('DROP INDEX IDX_3CA05366B54DBBCB ON tarea');
        $this->addSql('ALTER TABLE tarea DROP materia_id');
        $this->addSql('ALTER TABLE cronometro DROP FOREIGN KEY FK_D249178A80602616');
        $this->addSql('DROP INDEX IDX_D249178A80602616 ON cronometro');
        $this->addSql('ALTER TABLE cronometro DROP cronometro_id');
    }
}
