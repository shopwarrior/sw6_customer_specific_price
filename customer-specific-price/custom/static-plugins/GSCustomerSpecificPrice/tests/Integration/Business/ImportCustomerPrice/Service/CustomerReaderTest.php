<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerReader;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerReaderInterface;
use GSCustomerSpecificPriceTest\TestBase;
use Shopware\Core\Framework\Context;

/**
 * Class CustomerReaderTest
 * @package GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service
 */
class CustomerReaderTest extends TestBase
{
    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var CustomerReaderInterface
     */
    private CustomerReaderInterface $customerReader;

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
        $this->customerReader = $this->getContainer()->get(CustomerReader::class);
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetCustomerIdByCustomerNumber(): void
    {
        $customerId = $this->customerReader->getCustomerIdByCustomerNumber(self::CUSTOMER_NUMBER);
        self::assertSame($this->customerId, $customerId);
        self::assertNull($this->customerReader->getCustomerIdByCustomerNumber('-0'));
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testIsExists(): void
    {
        self::assertTrue($this->customerReader->isExists(self::CUSTOMER_NUMBER));
        self::assertFalse($this->customerReader->isExists('-1'));
    }
}
