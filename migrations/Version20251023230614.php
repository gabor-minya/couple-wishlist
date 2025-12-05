<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251023230614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE wishlist_items');
        $this->addSql('DROP TABLE persons');
        $this->addSql('DROP TABLE users');

        $this->addSql('CREATE TABLE persons (id INT NOT NULL, linked_user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_on_hold BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE wishlist_items (id INT NOT NULL, person_id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10,2) DEFAULT NULL, priority VARCHAR(20) NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, url VARCHAR(255) DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, note TEXT DEFAULT NULL, is_fulfilled BOOLEAN NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, last_modified_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');

        $this->addSql('CREATE INDEX IDX_A25CC7D3CC26EB02 ON persons (linked_user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE INDEX IDX_B5BB81B5217BBB47 ON wishlist_items (person_id)');

        $this->addSql('CREATE SEQUENCE persons_id_seq START 1');
        $this->addSql('ALTER TABLE persons ALTER COLUMN id SET DEFAULT nextval(\'persons_id_seq\')');

        $this->addSql('CREATE SEQUENCE users_id_seq START 1');
        $this->addSql('ALTER TABLE users ALTER COLUMN id SET DEFAULT nextval(\'users_id_seq\')');

        $this->addSql('CREATE SEQUENCE wishlist_items_id_seq START 1');
        $this->addSql('ALTER TABLE wishlist_items ALTER COLUMN id SET DEFAULT nextval(\'wishlist_items_id_seq\')');

        $this->addSql('ALTER TABLE persons ADD CONSTRAINT FK_A25CC7D3CC26EB02 FOREIGN KEY (linked_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE wishlist_items ADD CONSTRAINT FK_B5BB81B5217BBB47 FOREIGN KEY (person_id) REFERENCES persons (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE persons ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE persons ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE persons ALTER linked_user_id TYPE UUID');
        $this->addSql('ALTER TABLE wishlist_items ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE wishlist_items ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE wishlist_items ALTER person_id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
    }
}
