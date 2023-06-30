<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\DTO\PriceDTO;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities\CustomerPriceEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;

/**
 * Interface CustomerPriceServiceInterface
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
interface CustomerPriceServiceInterface
{
    /**
     * @param string $customerNumber
     * @param array<string,PriceDTO> $fetchedPrices
     * @return void
     */
    public function createFromFetchedPrices(string $customerNumber, array $fetchedPrices): void;

    /**
     * @param array $payload
     * @param Context|null $context
     * @return string
     */
    public function upsertOne(array $payload, ?Context $context): string;

    /**
     * @param string $id
     * @param Context|null $context
     * @return CustomerPriceEntity|null
     */
    public function getCustomerPriceById(string $id, ?Context $context): ?CustomerPriceEntity;

    /**
     * @param string $customerNumber
     * @param string $productNumber
     * @param PriceDTO $price
     * @return string|null
     */
    public function create(string $customerNumber, string $productNumber, PriceDTO $price): ?string;

    /**
     * @param string $customerId
     * @param Context|null $context
     * @param string[]|null $productIds
     * @return array<void>|array<string,CustomerPriceEntity>
     */
    public function getCustomerPersonalPrices(string $customerId, ?Context $context = null, ?array $productIds = []): array;

    /**
     * @param string $customerId
     * @return void
     */
    public function decreaseCustomerPrices(string $customerId): void;
}
