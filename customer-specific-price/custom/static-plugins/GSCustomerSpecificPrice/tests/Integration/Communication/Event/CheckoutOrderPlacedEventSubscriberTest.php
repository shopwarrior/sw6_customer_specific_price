<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest\Integration\Communication\Event;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities\CustomerPriceEntity;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerPriceService;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerPriceServiceInterface;
use GSCustomerSpecificPriceTest\TestBase;
use Shopware\Core\Checkout\Order\Aggregate\OrderCustomer\OrderCustomerEntity;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use GSCustomerSpecificPrice\Communication\Event\CheckoutOrderPlacedEventSubscriber;
use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Test\TestDefaults;

/**
 * Class FacadeTest
 * @package GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice
 */
class CheckoutOrderPlacedEventSubscriberTest extends TestBase
{
    use IntegrationTestBehaviour;

    /**
     * @var CheckoutOrderPlacedEventSubscriber
     */
    private $subscriber;

    /**
     * @var CustomerPriceServiceInterface
     */
    private CustomerPriceServiceInterface $customerPriceService;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var string
     */
    protected $context;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->context = Context::createDefaultContext();
        $customer = $this->createCustomer($this->context);
        $this->customerId = $customer->getId();
        $this->productId = $this->createProduct($this->context)->getParentId();
        $this->subscriber = $this->getContainer()->get(CheckoutOrderPlacedEventSubscriber::class);
        $this->customerPriceService = $this->getContainer()->get(CustomerPriceService::class);
    }

    /**
     * @return void
     */
    public function testOnCheckoutPlaced(): void
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

        $orderCustomer = $this->createMock(OrderCustomerEntity::class);
        $orderCustomer->method('getCustomerId')->willReturn($this->customerId);
        $order = $this->createMock(OrderEntity::class);
        $order->method('getOrderCustomer')->willReturn($orderCustomer);
        $event = new CheckoutOrderPlacedEvent(
            $this->context,
            $order,
            TestDefaults::SALES_CHANNEL
        );
        $this->subscriber->onCheckoutPlaced($event);
        $priceDefinition = $this->customerPriceService->getCustomerPriceById($id);
        self::assertSame(get_class($priceDefinition), CustomerPriceEntity::class);
        self::assertSame($priceDefinition->getNetValue(), 990.0);
        self::assertSame($priceDefinition->getGrossValue(), 990.0);
    }
}
