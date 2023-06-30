<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * Class CustomerRepository
 *
 * @package GSCustomerSpecificPrice\Persistence\Repository
 */
class CustomerRepository implements CustomerRepositoryInterface
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
     * @param string $customerNumber
     * @return string|null
     */
    public function getCustomerIdByCustomerNumber(string $customerNumber): ?string
    {
        $customerId = $this->connection->fetchOne(
            'SELECT `id`
                    FROM `customer`
                    WHERE `customer_number` like :customer_number
                    LIMIT 1',
            ['customer_number' => $customerNumber]
        );

        return $customerId? Uuid::fromBytesToHex($customerId): null;
    }
}
