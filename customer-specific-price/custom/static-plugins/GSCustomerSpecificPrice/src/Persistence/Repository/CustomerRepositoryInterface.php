<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Persistence\Repository;

/**
 * Interface CustomerRepositoryInterface
 *
 * @package GSCustomerSpecificPrice\Persistence\Repository
 */
interface CustomerRepositoryInterface
{
    /**
     * @param string $customerNumber
     * @return string|null
     */
    public function getCustomerIdByCustomerNumber(string $customerNumber): ?string;
}
