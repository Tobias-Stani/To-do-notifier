<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019155542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cronometro ADD materia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cronometro ADD CONSTRAINT FK_D249178AB54DBBCB FOREIGN KEY (materia_id) REFERENCES materia (id)');
        $this->addSql('CREATE INDEX IDX_D249178AB54DBBCB ON cronometro (materia_id)');
        $this->addSql('ALTER TABLE cuatrimestre ADD carrera_id INT NOT NULL');
        $this->addSql('ALTER TABLE cuatrimestre ADD CONSTRAINT FK_2746418EC671B40F FOREIGN KEY (carrera_id) REFERENCES carrera (id)');
        $this->addSql('CREATE INDEX IDX_2746418EC671B40F ON cuatrimestre (carrera_id)');
        $this->addSql('ALTER TABLE materia ADD cuatrimestre_id INT NOT NULL, ADD tarea_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materia ADD CONSTRAINT FK_6DF05284FB37697C FOREIGN KEY (cuatrimestre_id) REFERENCES cuatrimestre (id)');
        $this->addSql('ALTER TABLE materia ADD CONSTRAINT FK_6DF052846D5BDFE1 FOREIGN KEY (tarea_id) REFERENCES tarea (id)');
        $this->addSql('CREATE INDEX IDX_6DF05284FB37697C ON materia (cuatrimestre_id)');
        $this->addSql('CREATE INDEX IDX_6DF052846D5BDFE1 ON materia (tarea_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cronometro DROP FOREIGN KEY FK_D249178AB54DBBCB');
        $this->addSql('DROP INDEX IDX_D249178AB54DBBCB ON cronometro');
        $this->addSql('ALTER TABLE cronometro DROP materia_id');
        $this->addSql('ALTER TABLE cuatrimestre DROP FOREIGN KEY FK_2746418EC671B40F');
        $this->addSql('DROP INDEX IDX_2746418EC671B40F ON cuatrimestre');
        $this->addSql('ALTER TABLE cuatrimestre DROP carrera_id');
        $this->addSql('ALTER TABLE materia DROP FOREIGN KEY FK_6DF05284FB37697C');
        $this->addSql('ALTER TABLE materia DROP FOREIGN KEY FK_6DF052846D5BDFE1');
        $this->addSql('DROP INDEX IDX_6DF05284FB37697C ON materia');
        $this->addSql('DROP INDEX IDX_6DF052846D5BDFE1 ON materia');
        $this->addSql('ALTER TABLE materia DROP cuatrimestre_id, DROP tarea_id');
    }
}
