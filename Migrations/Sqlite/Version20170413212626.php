<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs! This block will be used as the migration description if getDescription() is not used.
 */
class Version20170413212626 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Profile picture support';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on "sqlite".');
        
        $this->addSql('CREATE TEMPORARY TABLE __temp__squadit_webapp_domain_model_squad AS SELECT persistence_object_identifier, name, description, picture FROM squadit_webapp_domain_model_squad');
        $this->addSql('DROP TABLE squadit_webapp_domain_model_squad');
        $this->addSql('CREATE TABLE squadit_webapp_domain_model_squad (persistence_object_identifier VARCHAR(40) NOT NULL COLLATE BINARY, name VARCHAR(80) NOT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, picture VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(persistence_object_identifier))');
        $this->addSql('INSERT INTO squadit_webapp_domain_model_squad (persistence_object_identifier, name, description, picture) SELECT persistence_object_identifier, name, description, picture FROM __temp__squadit_webapp_domain_model_squad');
        $this->addSql('DROP TABLE __temp__squadit_webapp_domain_model_squad');
        $this->addSql('CREATE UNIQUE INDEX flow_identity_squadit_webapp_domain_model_squad ON squadit_webapp_domain_model_squad (name)');
        $this->addSql('DROP INDEX IDX_BF57B404145ABC0A');
        $this->addSql('DROP INDEX IDX_BF57B404209C26A7');
        $this->addSql('CREATE TEMPORARY TABLE __temp__squadit_webapp_domain_model_squad_members_join AS SELECT webapp_squad, webapp_user FROM squadit_webapp_domain_model_squad_members_join');
        $this->addSql('DROP TABLE squadit_webapp_domain_model_squad_members_join');
        $this->addSql('CREATE TABLE squadit_webapp_domain_model_squad_members_join (webapp_squad VARCHAR(40) NOT NULL COLLATE BINARY, webapp_user VARCHAR(40) NOT NULL COLLATE BINARY, PRIMARY KEY(webapp_squad, webapp_user), CONSTRAINT FK_BF57B404209C26A7 FOREIGN KEY (webapp_squad) REFERENCES squadit_webapp_domain_model_squad (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BF57B404145ABC0A FOREIGN KEY (webapp_user) REFERENCES squadit_webapp_domain_model_user (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO squadit_webapp_domain_model_squad_members_join (webapp_squad, webapp_user) SELECT webapp_squad, webapp_user FROM __temp__squadit_webapp_domain_model_squad_members_join');
        $this->addSql('DROP TABLE __temp__squadit_webapp_domain_model_squad_members_join');
        $this->addSql('CREATE INDEX IDX_BF57B404145ABC0A ON squadit_webapp_domain_model_squad_members_join (webapp_user)');
        $this->addSql('CREATE INDEX IDX_BF57B404209C26A7 ON squadit_webapp_domain_model_squad_members_join (webapp_squad)');
        $this->addSql('DROP INDEX flow_identity_typo3_flow_security_account');
        $this->addSql('CREATE TEMPORARY TABLE __temp__typo3_flow_security_account AS SELECT persistence_object_identifier, accountidentifier, authenticationprovidername, credentialssource, creationdate, expirationdate, lastsuccessfulauthenticationdate, failedauthenticationcount, roleidentifiers FROM typo3_flow_security_account');
        $this->addSql('DROP TABLE typo3_flow_security_account');
        $this->addSql('CREATE TABLE typo3_flow_security_account (persistence_object_identifier VARCHAR(40) NOT NULL COLLATE BINARY, accountidentifier VARCHAR(255) NOT NULL COLLATE BINARY, authenticationprovidername VARCHAR(255) NOT NULL COLLATE BINARY, credentialssource VARCHAR(255) DEFAULT NULL COLLATE BINARY, creationdate DATETIME NOT NULL, expirationdate DATETIME DEFAULT NULL, lastsuccessfulauthenticationdate DATETIME DEFAULT NULL, failedauthenticationcount INTEGER DEFAULT NULL, roleidentifiers CLOB DEFAULT NULL, PRIMARY KEY(persistence_object_identifier))');
        $this->addSql('INSERT INTO typo3_flow_security_account (persistence_object_identifier, accountidentifier, authenticationprovidername, credentialssource, creationdate, expirationdate, lastsuccessfulauthenticationdate, failedauthenticationcount, roleidentifiers) SELECT persistence_object_identifier, accountidentifier, authenticationprovidername, credentialssource, creationdate, expirationdate, lastsuccessfulauthenticationdate, failedauthenticationcount, roleidentifiers FROM __temp__typo3_flow_security_account');
        $this->addSql('DROP TABLE __temp__typo3_flow_security_account');
        $this->addSql('CREATE UNIQUE INDEX flow_identity_typo3_flow_security_account ON typo3_flow_security_account (accountidentifier, authenticationprovidername)');
        $this->addSql('DROP INDEX IDX_7FD23F7DCFD0FFE7');
        $this->addSql('DROP INDEX UNIQ_7FD23F7D7D3656A4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__squadit_webapp_domain_model_user AS SELECT persistence_object_identifier, account, firstname, lastname, profilepicture FROM squadit_webapp_domain_model_user');
        $this->addSql('DROP TABLE squadit_webapp_domain_model_user');
        $this->addSql('CREATE TABLE squadit_webapp_domain_model_user (persistence_object_identifier VARCHAR(40) NOT NULL COLLATE BINARY, profilepicture VARCHAR(40) DEFAULT NULL, account VARCHAR(40) DEFAULT NULL COLLATE BINARY, firstname VARCHAR(80) NOT NULL COLLATE BINARY, lastname VARCHAR(80) NOT NULL COLLATE BINARY, PRIMARY KEY(persistence_object_identifier), CONSTRAINT FK_7FD23F7D87C2CF6D FOREIGN KEY (profilepicture) REFERENCES typo3_flow_resource_resource (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7FD23F7D7D3656A4 FOREIGN KEY (account) REFERENCES typo3_flow_security_account (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO squadit_webapp_domain_model_user (persistence_object_identifier, account, firstname, lastname, profilepicture) SELECT persistence_object_identifier, account, firstname, lastname, profilepicture FROM __temp__squadit_webapp_domain_model_user');
        $this->addSql('DROP TABLE __temp__squadit_webapp_domain_model_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7FD23F7D7D3656A4 ON squadit_webapp_domain_model_user (account)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7FD23F7D87C2CF6D ON squadit_webapp_domain_model_user (profilepicture)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on "sqlite".');
        
        $this->addSql('DROP INDEX flow_identity_squadit_webapp_domain_model_squad');
        $this->addSql('CREATE TEMPORARY TABLE __temp__squadit_webapp_domain_model_squad AS SELECT persistence_object_identifier, name, description, picture FROM squadit_webapp_domain_model_squad');
        $this->addSql('DROP TABLE squadit_webapp_domain_model_squad');
        $this->addSql('CREATE TABLE squadit_webapp_domain_model_squad (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(80) NOT NULL, description VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier))');
        $this->addSql('INSERT INTO squadit_webapp_domain_model_squad (persistence_object_identifier, name, description, picture) SELECT persistence_object_identifier, name, description, picture FROM __temp__squadit_webapp_domain_model_squad');
        $this->addSql('DROP TABLE __temp__squadit_webapp_domain_model_squad');
        $this->addSql('DROP INDEX IDX_BF57B404209C26A7');
        $this->addSql('DROP INDEX IDX_BF57B404145ABC0A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__squadit_webapp_domain_model_squad_members_join AS SELECT webapp_squad, webapp_user FROM squadit_webapp_domain_model_squad_members_join');
        $this->addSql('DROP TABLE squadit_webapp_domain_model_squad_members_join');
        $this->addSql('CREATE TABLE squadit_webapp_domain_model_squad_members_join (webapp_squad VARCHAR(40) NOT NULL, webapp_user VARCHAR(40) NOT NULL, PRIMARY KEY(webapp_squad, webapp_user))');
        $this->addSql('INSERT INTO squadit_webapp_domain_model_squad_members_join (webapp_squad, webapp_user) SELECT webapp_squad, webapp_user FROM __temp__squadit_webapp_domain_model_squad_members_join');
        $this->addSql('DROP TABLE __temp__squadit_webapp_domain_model_squad_members_join');
        $this->addSql('CREATE INDEX IDX_BF57B404209C26A7 ON squadit_webapp_domain_model_squad_members_join (webapp_squad)');
        $this->addSql('CREATE INDEX IDX_BF57B404145ABC0A ON squadit_webapp_domain_model_squad_members_join (webapp_user)');
        $this->addSql('DROP INDEX UNIQ_7FD23F7D87C2CF6D');
        $this->addSql('DROP INDEX UNIQ_7FD23F7D7D3656A4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__squadit_webapp_domain_model_user AS SELECT persistence_object_identifier, profilepicture, account, firstname, lastname FROM squadit_webapp_domain_model_user');
        $this->addSql('DROP TABLE squadit_webapp_domain_model_user');
        $this->addSql('CREATE TABLE squadit_webapp_domain_model_user (persistence_object_identifier VARCHAR(40) NOT NULL, account VARCHAR(40) DEFAULT NULL, firstname VARCHAR(80) NOT NULL, lastname VARCHAR(80) NOT NULL, profilepicture VARCHAR(255) DEFAULT NULL COLLATE BINARY, squad VARCHAR(40) DEFAULT NULL COLLATE BINARY, PRIMARY KEY(persistence_object_identifier))');
        $this->addSql('INSERT INTO squadit_webapp_domain_model_user (persistence_object_identifier, profilepicture, account, firstname, lastname) SELECT persistence_object_identifier, profilepicture, account, firstname, lastname FROM __temp__squadit_webapp_domain_model_user');
        $this->addSql('DROP TABLE __temp__squadit_webapp_domain_model_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7FD23F7D7D3656A4 ON squadit_webapp_domain_model_user (account)');
        $this->addSql('CREATE INDEX IDX_7FD23F7DCFD0FFE7 ON squadit_webapp_domain_model_user (squad)');
        $this->addSql('DROP INDEX flow_identity_typo3_flow_security_account');
        $this->addSql('CREATE TEMPORARY TABLE __temp__typo3_flow_security_account AS SELECT persistence_object_identifier, accountidentifier, authenticationprovidername, credentialssource, creationdate, expirationdate, lastsuccessfulauthenticationdate, failedauthenticationcount, roleidentifiers FROM typo3_flow_security_account');
        $this->addSql('DROP TABLE typo3_flow_security_account');
        $this->addSql('CREATE TABLE typo3_flow_security_account (persistence_object_identifier VARCHAR(40) NOT NULL, accountidentifier VARCHAR(255) NOT NULL, authenticationprovidername VARCHAR(255) NOT NULL, credentialssource VARCHAR(255) DEFAULT NULL, creationdate DATETIME NOT NULL, expirationdate DATETIME DEFAULT NULL, lastsuccessfulauthenticationdate DATETIME DEFAULT NULL, failedauthenticationcount INTEGER DEFAULT NULL, roleidentifiers CLOB DEFAULT NULL COLLATE BINARY, PRIMARY KEY(persistence_object_identifier))');
        $this->addSql('INSERT INTO typo3_flow_security_account (persistence_object_identifier, accountidentifier, authenticationprovidername, credentialssource, creationdate, expirationdate, lastsuccessfulauthenticationdate, failedauthenticationcount, roleidentifiers) SELECT persistence_object_identifier, accountidentifier, authenticationprovidername, credentialssource, creationdate, expirationdate, lastsuccessfulauthenticationdate, failedauthenticationcount, roleidentifiers FROM __temp__typo3_flow_security_account');
        $this->addSql('DROP TABLE __temp__typo3_flow_security_account');
        $this->addSql('CREATE UNIQUE INDEX flow_identity_typo3_flow_security_account ON typo3_flow_security_account (accountidentifier, authenticationprovidername)');
    }
}