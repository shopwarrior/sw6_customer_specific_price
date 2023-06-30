<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Persistence\Repository;

/**
 * Interface ProductRepositoryInterface
 *
 * @package GSCustomerSpecificPrice\Persistence\Repository
 */
interface ProductRepositoryInterface
{
    /**
     * @param string $productNumber
     * @return string|null
     */
    public function getProductIdByProductNumber(string $productNumber): ?string;

    /**
     * @return array<void>|array<string>
     */
    public function getProductNumbers(): array;
}
