<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221125131106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_formation (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, nom_module VARCHAR(50) NOT NULL, INDEX IDX_1A213E77BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, session_formation_id INT NOT NULL, module_formation_id INT NOT NULL, nb_jours INT NOT NULL, INDEX IDX_3DDCB9FF9C9D95AF (session_formation_id), INDEX IDX_3DDCB9FF3A53B0DC (module_formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_formation (id INT AUTO_INCREMENT NOT NULL, nb_places INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, intitule_session VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_formation_stagiaire (session_formation_id INT NOT NULL, stagiaire_id INT NOT NULL, INDEX IDX_B22E3C959C9D95AF (session_formation_id), INDEX IDX_B22E3C95BBA93DD6 (stagiaire_id), PRIMARY KEY(session_formation_id, stagiaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stagiaire (id INT AUTO_INCREMENT NOT NULL, nom_stagiaire VARCHAR(50) NOT NULL, prenom_stagiaire VARCHAR(50) NOT NULL, sexe VARCHAR(20) NOT NULL, date_naissance DATE NOT NULL, ville VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, telephone VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module_formation ADD CONSTRAINT FK_1A213E77BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF9C9D95AF FOREIGN KEY (session_formation_id) REFERENCES session_formation (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF3A53B0DC FOREIGN KEY (module_formation_id) REFERENCES module_formation (id)');
        $this->addSql('ALTER TABLE session_formation_stagiaire ADD CONSTRAINT FK_B22E3C959C9D95AF FOREIGN KEY (session_formation_id) REFERENCES session_formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session_formation_stagiaire ADD CONSTRAINT FK_B22E3C95BBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_formation DROP FOREIGN KEY FK_1A213E77BCF5E72D');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF9C9D95AF');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF3A53B0DC');
        $this->addSql('ALTER TABLE session_formation_stagiaire DROP FOREIGN KEY FK_B22E3C959C9D95AF');
        $this->addSql('ALTER TABLE session_formation_stagiaire DROP FOREIGN KEY FK_B22E3C95BBA93DD6');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE module_formation');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE session_formation');
        $this->addSql('DROP TABLE session_formation_stagiaire');
        $this->addSql('DROP TABLE stagiaire');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
