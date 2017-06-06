<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs! This block will be used as the migration description if getDescription() is not used.
 */
class Version20170601105153 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'FEATURE: Squalendar';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('CREATE TABLE squadit_webapp_domain_model_event (persistence_object_identifier VARCHAR(40) NOT NULL, squad VARCHAR(40) DEFAULT NULL, title VARCHAR(255) NOT NULL, startdate DATETIME NOT NULL, enddate DATETIME NOT NULL, description VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, INDEX IDX_1AE8BFFBCFD0FFE7 (squad), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE squadit_webapp_domain_model_event ADD CONSTRAINT FK_1AE8BFFBCFD0FFE7 FOREIGN KEY (squad) REFERENCES squadit_webapp_domain_model_squad (persistence_object_identifier)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('DROP TABLE squadit_webapp_domain_model_event');
    }
}