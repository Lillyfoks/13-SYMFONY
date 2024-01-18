<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116151748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, adress_id INT DEFAULT NULL, d_date DATE NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), INDEX IDX_F52993988486F9AC (adress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_details (id INT AUTO_INCREMENT NOT NULL, order_ref_id INT DEFAULT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, total_price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_845CA2C1E238517C (order_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_details_products (order_details_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_BBFA3DCD8C0FA77 (order_details_id), INDEX IDX_BBFA3DCD6C8A81A9 (products_id), PRIMARY KEY(order_details_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993988486F9AC FOREIGN KEY (adress_id) REFERENCES adresses (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1E238517C FOREIGN KEY (order_ref_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_details_products ADD CONSTRAINT FK_BBFA3DCD8C0FA77 FOREIGN KEY (order_details_id) REFERENCES order_details (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_details_products ADD CONSTRAINT FK_BBFA3DCD6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993988486F9AC');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1E238517C');
        $this->addSql('ALTER TABLE order_details_products DROP FOREIGN KEY FK_BBFA3DCD8C0FA77');
        $this->addSql('ALTER TABLE order_details_products DROP FOREIGN KEY FK_BBFA3DCD6C8A81A9');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP TABLE order_details_products');
    }
}
