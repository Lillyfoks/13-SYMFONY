<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123124604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE d_date d_date DATE NOT NULL');
        $this->addSql('ALTER TABLE order_details CHANGE total_price total_price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE products ADD brochure_filename VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE d_date d_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE order_details CHANGE total_price total_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE products DROP brochure_filename');
    }
}
