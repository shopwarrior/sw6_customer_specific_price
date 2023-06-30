<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice;

/**
 * Interface FacadeInterface
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice
 */
interface FacadeInterface
{
    /**
     * @param string $customerNumber
     * @param string|null $productNumber
     * @return int
     */
    public function import(string $customerNumber, ?string $productNumber): int;
}
