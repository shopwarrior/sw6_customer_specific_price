<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\ProductService;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\ProductServiceInterface;
use GSCustomerSpecificPriceTest\TestBase;
use Shopware\Core\Framework\Context;

/**
 * Class ProductServiceTest
 * @package GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service
 */
class ProductServiceTest extends TestBase
{
    /**
     * @var string
     */
    protected $productId;

    /**
     * @var ProductServiceInterface
     */
    private ProductServiceInterface $productService;

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $context = Context::createDefaultContext();
        $this->productId = $this->createProduct($context)->getParentId();
        $this->productService = $this->getContainer()->get(ProductService::class);
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetProductIdByProductNumber(): void
    {
        $productId = $this->productService->getProductIdByProductNumber(self::PRODUCT_NUMBER);
        self::assertSame($this->productId, $productId);
        self::assertNull(
            $this->productService->getProductIdByProductNumber('-0')
        );
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testIsExists(): void
    {
        self::assertTrue($this->productService->isExists(self::PRODUCT_NUMBER));
        self::assertFalse($this->productService->isExists('-1'));
    }
}
