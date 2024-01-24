<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124092321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_details_products DROP FOREIGN KEY FK_BBFA3DCD6C8A81A9');
        $this->addSql('ALTER TABLE order_details_products DROP FOREIGN KEY FK_BBFA3DCD8C0FA77');
        $this->addSql('DROP TABLE order_details_products');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_details_products (order_details_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_BBFA3DCD8C0FA77 (order_details_id), INDEX IDX_BBFA3DCD6C8A81A9 (products_id), PRIMARY KEY(order_details_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_details_products ADD CONSTRAINT FK_BBFA3DCD6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_details_products ADD CONSTRAINT FK_BBFA3DCD8C0FA77 FOREIGN KEY (order_details_id) REFERENCES order_details (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
