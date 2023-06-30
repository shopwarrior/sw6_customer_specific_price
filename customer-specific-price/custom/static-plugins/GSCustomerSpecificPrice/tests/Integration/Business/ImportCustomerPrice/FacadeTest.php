<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Facade;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\FacadeInterface;
use GSCustomerSpecificPriceTest\TestBase;
use Shopware\Core\Framework\Context;
use GSCustomerSpecificPrice\Persistence\GSCustomerSpecificPriceConstants;

/**
 * Class FacadeTest
 * @package GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice
 */
class FacadeTest extends TestBase
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
     * @var FacadeInterface
     */
    private FacadeInterface $facade;

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
        $this->facade = $this->getContainer()->get(Facade::class);
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCustomerDoesntExists(): void
    {
        self::assertSame($this->facade->import('-1'), GSCustomerSpecificPriceConstants::CUSTOMER_NOT_EXISTS);
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testProductDoesntExists(): void
    {
        self::assertSame(
            $this->facade->import(self::CUSTOMER_NUMBER, '-1'),
            GSCustomerSpecificPriceConstants::ARTICLE_NOT_EXISTS
        );
    }
}
