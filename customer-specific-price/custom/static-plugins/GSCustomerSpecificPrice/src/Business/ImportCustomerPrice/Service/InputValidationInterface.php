<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\DTO\PriceDTO;

/**
 * Interface ExternalPriceReaderInterface
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
interface InputValidationInterface
{

    /**
     * @param string $customerNumber
     * @return bool
     */
    public function isCustomerExists(string $customerNumber): bool;

    /**
     * @param string $productNumber
     * @return bool
     */
    public function isProductExists(string $productNumber): bool;
}
