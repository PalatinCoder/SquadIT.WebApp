<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs! This block will be used as the migration description if getDescription() is not used.
 */
class Version20170531065913 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Profilepicture support for squads';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        
        $this->addSql('ALTER TABLE squadit_webapp_domain_model_squad ADD profilepicture VARCHAR(40) DEFAULT NULL, DROP picture');
        $this->addSql('ALTER TABLE squadit_webapp_domain_model_squad ADD CONSTRAINT FK_EE964ABB87C2CF6D FOREIGN KEY (profilepicture) REFERENCES neos_flow_resourcemanagement_persistentresource (persistence_object_identifier)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EE964ABB87C2CF6D ON squadit_webapp_domain_model_squad (profilepicture)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        
        $this->addSql('ALTER TABLE squadit_webapp_domain_model_squad DROP FOREIGN KEY FK_EE964ABB87C2CF6D');
        $this->addSql('DROP INDEX UNIQ_EE964ABB87C2CF6D ON squadit_webapp_domain_model_squad');
        $this->addSql('ALTER TABLE squadit_webapp_domain_model_squad ADD picture VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP profilepicture');
    }
}
