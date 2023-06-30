<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * Class ProductRepository
 *
 * @package GSCustomerSpecificPrice\Persistence\Repository
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * Customer constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $productNumber
     * @return string|null
     */
    public function getProductIdByProductNumber(string $productNumber): ?string
    {
        $productId = $this->connection->fetchOne(
            'SELECT `id`
                    FROM `product`
                    WHERE `product_number` like :product_number
                    LIMIT 1',
            ['product_number' => $productNumber]
        );

        return $productId? Uuid::fromBytesToHex($productId): null;
    }

    /**
     * @return array<void>|string[]
     */
    public function getProductNumbers(): array
    {
        return $this->connection->fetchFirstColumn(
            'SELECT `product_number` FROM `product`'
        );
    }
}
