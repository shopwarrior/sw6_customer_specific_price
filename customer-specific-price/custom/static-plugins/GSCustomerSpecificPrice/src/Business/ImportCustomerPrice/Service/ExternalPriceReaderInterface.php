<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\DTO\PriceDTO;

/**
 * Interface ExternalPriceReaderInterface
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
interface ExternalPriceReaderInterface
{
    /**
     * @param string $customerNumber
     * @param string|null $productNumber
     * @return array<string,PriceDTO>|array<void>
     */
    public function fetchPrices(string $customerNumber, ?string $productNumber): array;
}
