<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs! This block will be used as the migration description if getDescription() is not used.
 */
class Version20161122134809 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Create User and Squad schema';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        //$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on "sqlite".');

        $this->addSql('CREATE TABLE squadit_webapp_domain_model_user (persistence_object_identifier VARCHAR(40) NOT NULL, squad VARCHAR(40) DEFAULT NULL, firstname VARCHAR(80) NOT NULL, lastname VARCHAR(80) NOT NULL, profilepicture VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier))');
        $this->addSql('CREATE INDEX IDX_7FD23F7DCFD0FFE7 ON squadit_webapp_domain_model_user (squad)');
        $this->addSql('CREATE TABLE squadit_webapp_domain_model_squad (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(80) NOT NULL, description VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier))');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        //$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on "sqlite".');

        $this->addSql('DROP TABLE squadit_webapp_domain_model_user');
        $this->addSql('DROP TABLE squadit_webapp_domain_model_squad');
    }
}
