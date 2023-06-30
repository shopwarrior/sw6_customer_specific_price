<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\DTO\PriceDTO;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities\CustomerPriceEntity;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\Defaults;

/**
 * Class CustomerPriceService
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
class CustomerPriceService implements CustomerPriceServiceInterface
{
    /**
     * @var CustomerReaderInterface
     */
    private CustomerReaderInterface $customerReader;

    /**
     * @var EntityRepository
     */
    private EntityRepository $customerPriceRepository;

    /**
     * @var ProductServiceInterface
     */
    private ProductServiceInterface $productService;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var SystemConfigService
     */
    private SystemConfigService $systemConfigService;

    /**
     * @param CustomerReaderInterface $customerReader
     * @param EntityRepository $customerPriceRepository
     * @param ProductServiceInterface $ProductService
     * @param LoggerInterface $logger
     * @param SystemConfigService $systemConfigService
     */
    public function __construct(
        CustomerReaderInterface $customerReader,
        EntityRepository $customerPriceRepository,
        ProductServiceInterface $productService,
        LoggerInterface $logger,
        SystemConfigService $systemConfigService
    ) {
        $this->customerReader = $customerReader;
        $this->customerPriceRepository = $customerPriceRepository;
        $this->productService = $productService;
        $this->logger = $logger;
        $this->systemConfigService = $systemConfigService;
    }

    /**
     * @param string $customerNumber
     * @param array<string,PriceDTO> $fetchedPrices
     * @return void
     */
    public function createFromFetchedPrices(string $customerNumber, array $fetchedPrices): void
    {
        foreach ($fetchedPrices as $productNumber => $priceDTO) {
            $this->create($customerNumber, $productNumber, $priceDTO);
        }
    }

    /**
     * @param array $payload
     * @param Context|null $context
     * @return string
     */
    public function upsertOne(array $payload, ?Context $context = null): string
    {
        $context = $context ?? Context::createDefaultContext();
        $this->customerPriceRepository->upsert([$payload], $context);

        return $payload['id'];
    }

    /**
     * @param string $id
     * @param Context|null $context
     * @return CustomerPriceEntity|null
     */
    public function getCustomerPriceById(string $id, ?Context $context = null): ?CustomerPriceEntity
    {
        $context = $context ?? Context::createDefaultContext();
        return $this->customerPriceRepository->search(new Criteria([$id]), $context)->first();
    }

    /**
     * @param string $customerId
     * @param Context|null $context
     * @param string[]|null $productIds
     * @return array<void>|array<string,CustomerPriceEntity>
     */
    public function getCustomerPersonalPrices(string $customerId, ?Context $context = null, ?array $productIds = null): array
    {
        $context = $context ?? Context::createDefaultContext();
        $result = [];
        $criteria = (new Criteria())
            ->addFilter(
                new EqualsFilter('customerId', $customerId)
            );
        if (!empty($productIds)) {
            $criteria->addFilter(
                new EqualsAnyFilter('productId', $productIds)
            );
        }
        $personalPrices = $this->customerPriceRepository->search($criteria, $context)->getEntities()->getElements();
        foreach ($personalPrices as $personalPrice) {
            /**
             * @var CustomerPriceEntity $personalPrice
             */
            $result[$personalPrice->getProductId()] = $personalPrice;
        }

        return $result;
    }

    /**
     * @param string $customerNumber
     * @param string $productNumber
     * @param PriceDTO $price
     * @return string|null
     */
    public function create(string $customerNumber, string $productNumber, PriceDTO $price): ?string
    {
        $customerId = $this->customerReader->getCustomerIdByCustomerNumber($customerNumber);
        $productId = $this->productService->getProductIdByProductNumber($productNumber);

        if (is_null($customerId) || is_null($productId)) {
            return null;
        }

        $payload = [
            'id' => md5($customerId . $productId),
            'productId' => $productId,
            'customerId' => $customerId,
            'netValue' => $price->netValue,
            'grossValue' => $price->grossValue,
            'listNetValue' => $price->listNetValue,
            'listGrossValue' => $price->listGrossValue,
        ];
        if ($this->systemConfigService->getBool('GSCustomerSpecificPrice.config.logDebug')) {
            $this->logger->info(
                "Insert customer specific price for customer number {$customerNumber} ($customerId) with article {$productNumber} ({$productId})",
                $payload
            );
        }

        return $this->upsertOne($payload);
    }

    /**
     * @param string $customerId
     * @return void
     */
    public function decreaseCustomerPrices(string $customerId): void
    {
        $prices = $this->getCustomerPersonalPrices($customerId);
        foreach ($prices as $price) {
            $netPrice = $price->getNetValue()*0.99;
            if ($netPrice>=$this->systemConfigService->getInt('GSCustomerSpecificPrice.config.threshold')) {
                $payload = [
                    'id' => $price->getId(),
                    'productId' => $price->getProductId(),
                    'customerId' => $price->getCustomerId(),
                    'netValue' => $netPrice,
                    'grossValue' => $price->getGrossValue()*0.99,
                    'listNetValue' => $price->getListNetValue()*0.99,
                    'listGrossValue' => $price->getListGrossValue()*0.99,
                ];
                $this->logger->info(
                    "Decrease customer specific prices",
                    $payload
                );
                $this->upsertOne($payload);
            }
        }
    }
}
