<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Test\TestCaseBase\BasicTestDataBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Test\TestDefaults;

/**
 * Class TestBase
 * @package GSCustomerSpecificPriceTest
 */
class TestBase extends TestCase
{
    use KernelTestBehaviour, BasicTestDataBehaviour;

    public const PRODUCT_NUMBER = 'GSCustomerSpecificPrice-ARTICLE';
    public const CUSTOMER_NUMBER = 'GSCustomerSpecificPrice-USER';

    /**
     * @var Connection
     */
    protected Connection $connection;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = $this->getContainer()->get(Connection::class);
        $this->connection->beginTransaction();
    }

    /**
     * @return void
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->connection->rollBack();
    }

    /**
     * @param Context $context
     * @return CustomerEntity
     */
    protected function createCustomer(Context $context): CustomerEntity
    {
        $customerId = md5('GSCustomerSpecificPrice');
        $addressId = md5('GSCustomerSpecificPrice');

        $data = [
            [
                'id' => $customerId,
                'salesChannelId' => TestDefaults::SALES_CHANNEL,
                'defaultShippingAddress' => [
                    'id' => $addressId,
                    'firstName' => 'Max',
                    'lastName' => 'Mustermann',
                    'street' => 'Musterstraße 1',
                    'city' => 'Schöppingen',
                    'zipcode' => '12345',
                    'salutationId' => $this->getValidSalutationId(),
                    'country' => ['name' => 'Germany'],
                ],
                'defaultBillingAddressId' => $addressId,
                'defaultPaymentMethodId' => $this->getValidPaymentMethodId(),
                'groupId' => TestDefaults::FALLBACK_CUSTOMER_GROUP,
                'email' => 'test@example.com',
                'password' => 'test12345',
                'firstName' => 'Max',
                'lastName' => 'Mustermann',
                'salutationId' => $this->getValidSalutationId(),
                'customerNumber' => self::CUSTOMER_NUMBER,
            ],
        ];

        $repo = $this->getContainer()->get('customer.repository');

        $repo->upsert($data, $context);

        return $repo->search(new Criteria([$customerId]), $context)->first();
    }

    /**
     * @param Context $context
     * @return ProductEntity|null
     */
    protected function createProduct(Context $context): ?ProductEntity
    {
        /** @var EntityRepositoryInterface $productRepository */
        $productRepository = $this->getContainer()->get('product.repository');

        $data = [
            'name' => 'Test product',
            'productNumber' => self::PRODUCT_NUMBER,
            'stock' => 1,
            'price' => [
                ['currencyId' => Defaults::CURRENCY, 'gross' => 19.99, 'net' => 10, 'linked' => false],
            ],
            'manufacturer' => ['name' => 'shopware AG'],
            'tax' => ['id' => $this->getValidTaxId(), 'name' => 'testTaxRate', 'taxRate' => 15],
            'categories' => [
                ['name' => 'Test category'],
            ],
            'visibilities' => [
                [
                    'salesChannelId' => TestDefaults::SALES_CHANNEL,
                    'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                ],
            ],
        ];

        $data['parent'] = $data;
        $data['id'] = Uuid::randomHex();
        $data['productNumber'] = 'var'.self::PRODUCT_NUMBER;

        $productRepository->upsert([$data], $context);

        return $productRepository->search(new Criteria([$data['id']]), $context)->first();
    }
}
