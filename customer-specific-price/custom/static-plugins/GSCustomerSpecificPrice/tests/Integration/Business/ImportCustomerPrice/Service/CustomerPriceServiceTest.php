<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\DTO\PriceDTO;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerPriceServiceInterface;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerPriceService;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities\CustomerPriceEntity;
use GSCustomerSpecificPriceTest\TestBase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * Class CustomerReaderTest
 * @package GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service
 */
class CustomerPriceServiceTest extends TestBase
{
    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var CustomerPriceServiceInterface
     */
    private CustomerPriceServiceInterface $customerPriceService;

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $context = Context::createDefaultContext();
        $this->productId = $this->createProduct($context)->getParentId();
        $customer = $this->createCustomer($context);
        $this->customerId = $customer->getId();
        $this->customerPriceService = $this->getContainer()->get(CustomerPriceService::class);
    }

    /**
     * @return void
     */
    public function testUpsert(): void
    {
        $id = Uuid::randomHex();
        $_id = $this->customerPriceService->upsertOne([
            'id' => $id,
            'productId' => $this->productId,
            'customerId' => $this->customerId,
            'netValue' => 1,
            'grossValue' => 2,
            'listNetValue' => 3,
            'listGrossValue' => 4,
        ]);
        $priceDefinition = $this->customerPriceService->getCustomerPriceById($id);
        self::assertSame(get_class($priceDefinition), CustomerPriceEntity::class);
        self::assertSame($_id, $id);
        self::assertSame($priceDefinition->getId(), $id);
        self::assertSame($priceDefinition->getCustomerId(), $this->customerId);
        self::assertSame($priceDefinition->getProductId(), $this->productId);
    }

    /**
     * @return void
     */
    public function testCreateNotExistedArticle(): void
    {
        $id = Uuid::randomHex();
        $this->customerPriceService->create(
            self::CUSTOMER_NUMBER,
            '-1',
            new PriceDTO(1, 1, 1, 1)
        );
        $priceDefinition = $this->customerPriceService->getCustomerPriceById($id);
        self::assertNull($priceDefinition);
    }

    /**
     * @return void
     */
    public function testCreateNotExistedCustomer(): void
    {
        $id = Uuid::randomHex();
        $this->customerPriceService->create(
            '-1',
            self::PRODUCT_NUMBER,
            new PriceDTO(1, 1, 1, 1)
        );
        $priceDefinition = $this->customerPriceService->getCustomerPriceById($id);
        self::assertNull($priceDefinition);
    }

    /**
     * @return void
     */
    public function testCreate(): void
    {
        $_id = $this->customerPriceService->create(
            self::CUSTOMER_NUMBER,
            self::PRODUCT_NUMBER,
            new PriceDTO(1, 1, 1, 1)
        );
        $priceDefinition = $this->customerPriceService->getCustomerPriceById($_id);
        self::assertSame(get_class($priceDefinition), CustomerPriceEntity::class);
        self::assertSame($priceDefinition->getId(), $_id);
        self::assertSame($priceDefinition->getCustomerId(), $this->customerId);
        self::assertSame($priceDefinition->getProductId(), $this->productId);
    }
}
