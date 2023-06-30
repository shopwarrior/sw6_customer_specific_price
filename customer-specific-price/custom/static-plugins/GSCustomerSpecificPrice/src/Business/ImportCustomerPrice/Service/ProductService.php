<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use Psr\Log\LoggerInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use GSCustomerSpecificPrice\Persistence\Repository\ProductRepositoryInterface;

/**
 * Class ProductService
 *
 * @package GSCustomerSpecificPrice\Business\ImportProductPrice\Service
 */
class ProductService implements ProductServiceInterface
{
    /**
     * @var SystemConfigService
     */
    private SystemConfigService $systemConfigService;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param SystemConfigService $systemConfigService
     * @param ProductRepositoryInterface $productRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        SystemConfigService $systemConfigService,
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * @param string $productNumber
     * @return bool
     */
    public function isExists(string $productNumber): bool
    {
        $productId = $this->getProductIdByProductNumber($productNumber);
        if ($this->systemConfigService->getBool('GSCustomerSpecificPrice.config.logDebug')) {
            $this->logger->info(
                is_null($productId)?
                "Sucessfully fetched product with productNumber {$productNumber}":
                "Product with productNumber {$productNumber} doesn't exist"
            );
        }

        return !is_null($productId);
    }

    /**
     * @param string $productNumber
     * @return string|null
     */
    public function getProductIdByProductNumber(string $productNumber): ?string
    {
        return $this->productRepository->getProductIdByProductNumber($productNumber);
    }
}
