<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429185919 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event (
		id INT AUTO_INCREMENT NOT NULL, 
		location_id INT NOT NULL,
		name VARCHAR(40) NOT NULL, 
		date DATETIME NOT NULL, 
		INDEX IDX_3BAE0AA764D218E (location_id),
		PRIMARY KEY(id)
	) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE location (
		id INT AUTO_INCREMENT NOT NULL, 
		beacon INT NOT NULL, qrcode VARCHAR(40) NOT NULL, 
		description LONGTEXT NOT NULL,
		PRIMARY KEY(id)
	) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user (
		id INT AUTO_INCREMENT NOT NULL, 
		token VARCHAR(40) NOT NULL, 
		name VARCHAR(50) NOT NULL, 
		email VARCHAR(300) NOT NULL, 
		password VARCHAR(150) NOT NULL, 
		PRIMARY KEY(id)
	) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE action (
		id INT AUTO_INCREMENT NOT NULL, 
		event_id INT NOT NULL, 
		user_id INT NOT NULL,
		date DATETIME NOT NULL, 
		INDEX IDX_47CC8C9271F7E88B (event_id),
		INDEX IDX_47CC8C92A76ED395 (user_id),
		PRIMARY KEY(id)
	) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA764D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C9271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA764D218E');
	$this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C9271F7E88B');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92A76ED395');

        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE action');
    }
}
