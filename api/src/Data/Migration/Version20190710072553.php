<?php declare(strict_types=1);

namespace Api\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190710072553 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql(
            'CREATE TABLE oauth_auth_codes
            (
                identifier VARCHAR(80) NOT NULL,
                expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                user_identifier UUID NOT NULL,
                client VARCHAR(255) NOT NULL,
                scopes JSON NOT NULL,
                redirectUri VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY(identifier)
            )'
        );

        $this->addSql(
            'CREATE TABLE oauth_access_tokens
            (
                identifier VARCHAR(80) NOT NULL,
                expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                user_identifier UUID NOT NULL,
                client VARCHAR(255) NOT NULL,
                scopes JSON NOT NULL,
                PRIMARY KEY(identifier)
            )'
        );

        $this->addSql(
            'CREATE TABLE oauth_refresh_tokens
            (
                identifier VARCHAR(80) NOT NULL,
                access_token_identifier VARCHAR(80) NOT NULL,
                expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(identifier)
            )'
        );

        $this->addSql('
            CREATE INDEX IDX_5AB6878E5675DC ON oauth_refresh_tokens (access_token_identifier)
        ');

        $this->addSql(
            'ALTER TABLE oauth_refresh_tokens
            ADD CONSTRAINT FK_5AB6878E5675DC FOREIGN KEY (access_token_identifier)
            REFERENCES oauth_access_tokens (identifier)
            ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE'
        );

        $this->addSql('ALTER TABLE user_users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE user_users ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER email DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE oauth_auth_codes');
        $this->addSql('DROP TABLE oauth_access_tokens');
        $this->addSql('DROP TABLE oauth_refresh_tokens');
        $this->addSql('ALTER TABLE user_users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE user_users ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE user_users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_users ALTER email DROP DEFAULT');
    }
}
