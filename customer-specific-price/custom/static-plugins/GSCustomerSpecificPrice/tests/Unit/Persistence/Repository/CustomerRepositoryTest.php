<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest\Unit\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Persistence\Repository\CustomerRepository;
use GSCustomerSpecificPrice\Persistence\Repository\CustomerRepositoryInterface;
use GSCustomerSpecificPriceTest\TestBase;
use Shopware\Core\Framework\Context;

/**
 * Class CustomerRepositoryTest
 * @package GSCustomerSpecificPriceTest\Unit\Business\ImportCustomerPrice\Service
 */
class CustomerRepositoryTest extends TestBase
{
    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var string
     */
    protected $customerId;

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
        $this->customerRepository = new CustomerRepository(
            $this->connection
        );
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function testGetCustomerIdByCustomerNumber(): void
    {
        $customerId = $this->customerRepository->getCustomerIdByCustomerNumber(self::CUSTOMER_NUMBER);
        self::assertSame($this->customerId, $customerId);
        self::assertNull($this->customerRepository->getCustomerIdByCustomerNumber('-0'));
    }
}
