<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220617090752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE typage ADD commande_id INT DEFAULT NULL, ADD type_couteau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE typage ADD CONSTRAINT FK_E0FD05F682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE typage ADD CONSTRAINT FK_E0FD05F6829EA801 FOREIGN KEY (type_couteau_id) REFERENCES type_couteau (id)');
        $this->addSql('CREATE INDEX IDX_E0FD05F682EA2E54 ON typage (commande_id)');
        $this->addSql('CREATE INDEX IDX_E0FD05F6829EA801 ON typage (type_couteau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE typage DROP FOREIGN KEY FK_E0FD05F682EA2E54');
        $this->addSql('ALTER TABLE typage DROP FOREIGN KEY FK_E0FD05F6829EA801');
        $this->addSql('DROP INDEX IDX_E0FD05F682EA2E54 ON typage');
        $this->addSql('DROP INDEX IDX_E0FD05F6829EA801 ON typage');
        $this->addSql('ALTER TABLE typage DROP commande_id, DROP type_couteau_id');
    }
}
