<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

/**
 * Interface ProductServiceInterface
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
interface ProductServiceInterface
{
    /**
     * @param string $productNumber
     * @return bool
     */
    public function isExists(string $productNumber): bool;

    /**
     * @param string $productNumber
     * @return string|null
     */
    public function getProductIdByProductNumber(string $productNumber): ?string;
}
