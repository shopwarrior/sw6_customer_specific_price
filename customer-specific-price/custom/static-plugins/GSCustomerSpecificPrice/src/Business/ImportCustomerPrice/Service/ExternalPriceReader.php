<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\DTO\PriceDTO;
use Psr\Log\LoggerInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use GSCustomerSpecificPrice\Persistence\Repository\ProductRepositoryInterface;

/**
 * Class ExternalPriceReader
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
class ExternalPriceReader implements ExternalPriceReaderInterface
{
    /**
     * @var SystemConfigService
     */
    private SystemConfigService $systemConfigService;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param SystemConfigService $systemConfigService
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        SystemConfigService $systemConfigService,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->logger = $logger;
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $customerNumber
     * @param string|null $productNumber
     * @return array<string,PriceDTO>|array<void>
     */
    public function fetchPrices(string $customerNumber, ?string $productNumber = null): array
    {
        return $this->convertPrices(
            $this->getPrices($customerNumber, $productNumber)
        );
    }

    /**
     * @param string $customerNumber
     * @param string|null $productNumber
     * @return array<void>|array<string,array<string, mixed>>
     */
    private function getPrices(string $customerNumber, ?string $productNumber): array
    {
        $pricesList = [];
        $productNumbers = $productNumber? [$productNumber]: $this->productRepository->getProductNumbers();

        foreach ($productNumbers as $productNumber) {
            try {
                $pricesList[$productNumber] = [
                    'netValue' => mt_rand(1, 1000),
                    'grossValue' => mt_rand(1, 1000),
                    'listNetValue' => mt_rand(1, 1000),
                    'listGrossValue' => mt_rand(1, 1000),
                ];
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
            if ($this->systemConfigService->getBool('GSCustomerSpecificPrice.config.logDebug')) {
                $this->logger->info(
                    "Fetched prices for {$productNumber}",
                    $pricesList
                );
            }
        }

        return $pricesList;
    }

    /**
     * @param array $rawPrices
     * @return array<void>|array<array<string, mixed>>
     */
    private function convertPrices(array $rawPrices): array
    {
        $convertedPrices = [];

        foreach ($rawPrices as $articleNumber => $price) {
            $convertedPrices[$articleNumber] = new PriceDTO(
                (float)$price['netValue'],
                (float)$price['grossValue'],
                (float)$price['listNetValue'],
                (float)$price['listGrossValue'],
            );
        }
        if ($this->systemConfigService->getBool('GSCustomerSpecificPrice.config.logDebug')) {
            $this->logger->info(
                'Fetched price for ' . count($convertedPrices) . ' articles',
                array_keys($convertedPrices)
            );
        }

        return $convertedPrices;
    }
}
