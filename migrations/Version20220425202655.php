<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425202655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, prestation_id INT DEFAULT NULL, client_id INT NOT NULL, nb_couteau INT NOT NULL, UNIQUE INDEX UNIQ_6EEAA67D9E45C554 (prestation_id), INDEX IDX_6EEAA67D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664109E45C554');
        $this->addSql('DROP INDEX IDX_FE8664109E45C554 ON facture');
        $this->addSql('ALTER TABLE facture ADD commande_id INT DEFAULT NULL, DROP prestation_id');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FE86641082EA2E54 ON facture (commande_id)');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FADA76ED395');
        $this->addSql('DROP INDEX IDX_51C88FADA76ED395 ON prestation');
        $this->addSql('ALTER TABLE prestation ADD debut DATETIME NOT NULL, ADD fin DATETIME NOT NULL, DROP user_id, DROP nb_couteau, DROP date_debut, DROP date_fin');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641082EA2E54');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP INDEX UNIQ_FE86641082EA2E54 ON facture');
        $this->addSql('ALTER TABLE facture ADD prestation_id INT NOT NULL, DROP commande_id');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664109E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id)');
        $this->addSql('CREATE INDEX IDX_FE8664109E45C554 ON facture (prestation_id)');
        $this->addSql('ALTER TABLE prestation ADD user_id INT NOT NULL, ADD nb_couteau INT NOT NULL, ADD date_debut DATETIME NOT NULL, ADD date_fin DATETIME NOT NULL, DROP debut, DROP fin');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_51C88FADA76ED395 ON prestation (user_id)');
    }
}
