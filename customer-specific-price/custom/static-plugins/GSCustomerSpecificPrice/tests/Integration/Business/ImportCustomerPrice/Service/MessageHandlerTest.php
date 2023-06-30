<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities\CustomerPriceEntity;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerPriceService;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerPriceServiceInterface;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\MessageHandler;
use GSCustomerSpecificPriceTest\TestBase;
use Shopware\Core\Framework\Context;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\MessageQueue\Message\ChangeCustomerPriceMessage;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * Class MessageHandlerTest
 * @package GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service
 */
class MessageHandlerTest extends TestBase
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
     * @var MessageHandler
     */
    private MessageHandler $messageHandler;

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $context = Context::createDefaultContext();
        $customer = $this->createCustomer($context);
        $this->customerId = $customer->getId();
        $this->productId = $this->createProduct($context)->getParentId();
        $this->messageHandler = $this->getContainer()->get(MessageHandler::class);
        $this->customerPriceService = $this->getContainer()->get(CustomerPriceService::class);
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testHandleWithoutPriceUpdate(): void
    {
        $id = $this->customerPriceService->upsertOne([
            'id' => Uuid::randomHex(),
            'productId' => $this->productId,
            'customerId' => $this->customerId,
            'netValue' => 100,
            'grossValue' => 100,
            'listNetValue' => 100,
            'listGrossValue' => 100,
        ]);
        $priceDefinition = $this->customerPriceService->getCustomerPriceById($id);
        self::assertSame(get_class($priceDefinition), CustomerPriceEntity::class);
        self::assertSame($priceDefinition->getNetValue(), 100.0);
        self::assertSame($priceDefinition->getGrossValue(), 100.0);

        $message = new ChangeCustomerPriceMessage($this->customerId);
        $this->messageHandler->__invoke($message);
        $priceDefinition = $this->customerPriceService->getCustomerPriceById($id);
        self::assertSame(get_class($priceDefinition), CustomerPriceEntity::class);
        self::assertSame($priceDefinition->getNetValue(), 100.0);
        self::assertSame($priceDefinition->getGrossValue(), 100.0);
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testHandleWithPriceUpdate(): void
    {
        $id = $this->customerPriceService->upsertOne([
            'id' => Uuid::randomHex(),
            'productId' => $this->productId,
            'customerId' => $this->customerId,
            'netValue' => 1000,
            'grossValue' => 1000,
            'listNetValue' => 1000,
            'listGrossValue' => 1000,
        ]);
        $priceDefinition = $this->customerPriceService->getCustomerPriceById($id);
        self::assertSame(get_class($priceDefinition), CustomerPriceEntity::class);
        self::assertSame($priceDefinition->getNetValue(), 1000.0);
        self::assertSame($priceDefinition->getGrossValue(), 1000.0);

        $message = new ChangeCustomerPriceMessage($this->customerId);
        $this->messageHandler->__invoke($message);
        $priceDefinition = $this->customerPriceService->getCustomerPriceById($id);
        self::assertSame(get_class($priceDefinition), CustomerPriceEntity::class);
        self::assertSame($priceDefinition->getNetValue(), 990.0);
        self::assertSame($priceDefinition->getGrossValue(), 990.0);
    }
}
