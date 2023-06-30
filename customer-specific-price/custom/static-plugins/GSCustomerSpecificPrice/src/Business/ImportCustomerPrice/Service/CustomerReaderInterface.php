<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

/**
 * Interface CustomerReaderInterface
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
interface CustomerReaderInterface
{
    /**
     * @param string $customerNumber
     * @return bool
     */
    public function isExists(string $customerNumber): bool;

    /**
     * @param string $customerNumber
     * @return string|null
     */
    public function getCustomerIdByCustomerNumber(string $customerNumber): ?string;
}
