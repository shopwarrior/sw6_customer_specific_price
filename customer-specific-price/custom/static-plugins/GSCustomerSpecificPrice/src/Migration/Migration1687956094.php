<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1687956094 extends MigrationStep
{
    /**
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1687956094;
    }

    /**
     * @param Connection $connection
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function update(Connection $connection): void
    {
        $query = <<<SQL
CREATE TABLE IF NOT EXISTS `gs_customer_price` (
    `id` BINARY(16) NOT NULL,
    `product_id` BINARY(16) NOT NULL,
    `customer_id` BINARY(16) NOT NULL,
    `net_value` float NOT NULL,
    `gross_value` float NOT NULL,
    `list_net_value` float NOT NULL,
    `list_gross_value` float NOT NULL,
    `created_at` DATETIME (3) NOT NULL,
    `updated_at` DATETIME (3) DEFAULT NULL,
    PRIMARY KEY (`id`),

    CONSTRAINT `fk.gs_customer_price.product_id`
        FOREIGN KEY (`product_id`)
        REFERENCES `product` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.gs_customer_price.customer_id`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
SQL;
        $connection->executeStatement($query);
    }

    /**
     * @param Connection $connection
     * @return void
     */
    public function updateDestructive(Connection $connection): void
    {
    }
}
