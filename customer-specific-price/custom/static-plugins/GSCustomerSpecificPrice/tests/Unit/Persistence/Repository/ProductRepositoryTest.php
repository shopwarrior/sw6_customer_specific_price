<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest\Unit\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Persistence\Repository\ProductRepository;
use GSCustomerSpecificPrice\Persistence\Repository\ProductRepositoryInterface;
use GSCustomerSpecificPriceTest\TestBase;
use Shopware\Core\Framework\Context;

/**
 * Class ProductRepositoryTest
 * @package GSCustomerSpecificPriceTest\Unit\Business\ImportCustomerPrice\Service
 */
class ProductRepositoryTest extends TestBase
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var string
     */
    protected $productNumber;

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $context = Context::createDefaultContext();
        $this->productId = $this->createProduct($context)->getParentId();
        $this->productRepository = new ProductRepository(
            $this->connection
        );
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetProductIdByProductNumber(): void
    {
        $productId = $this->productRepository->getProductIdByProductNumber(self::PRODUCT_NUMBER);
        self::assertSame($this->productId, $productId);
        self::assertNull($this->productRepository->getProductIdByProductNumber('-0'));
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetProductNumbers(): void
    {
        $productIds = $this->productRepository->getProductNumbers();
        self::assertNotEmpty($productIds);
    }
}
