<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220709164926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, adresse VARCHAR(255) NOT NULL, cp VARCHAR(10) NOT NULL, ville VARCHAR(255) NOT NULL, INDEX IDX_C35F0816A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, prestation_id INT NOT NULL, facture_id INT DEFAULT NULL, date_facturation DATE NOT NULL, details JSON NOT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), INDEX IDX_6EEAA67D9E45C554 (prestation_id), UNIQUE INDEX UNIQ_6EEAA67D7F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(30) NOT NULL, client VARCHAR(150) NOT NULL, paye TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre_or (id INT AUTO_INCREMENT NOT NULL, commentaire LONGTEXT NOT NULL, note INT NOT NULL, creation DATE NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, debut DATETIME NOT NULL, fin DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typage (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, type_couteau_id INT DEFAULT NULL, nb_couteau INT NOT NULL, INDEX IDX_E0FD05F682EA2E54 (commande_id), INDEX IDX_E0FD05F6829EA801 (type_couteau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_couteau (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, tarif INT NOT NULL, description LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, commentaire_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, telephone VARCHAR(20) NOT NULL, entreprise VARCHAR(200) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649BA9CD190 (commentaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D9E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE typage ADD CONSTRAINT FK_E0FD05F682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE typage ADD CONSTRAINT FK_E0FD05F6829EA801 FOREIGN KEY (type_couteau_id) REFERENCES type_couteau (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES livre_or (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE typage DROP FOREIGN KEY FK_E0FD05F682EA2E54');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D7F2DEE08');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BA9CD190');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D9E45C554');
        $this->addSql('ALTER TABLE typage DROP FOREIGN KEY FK_E0FD05F6829EA801');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816A76ED395');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE livre_or');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE typage');
        $this->addSql('DROP TABLE type_couteau');
        $this->addSql('DROP TABLE user');
    }
}
