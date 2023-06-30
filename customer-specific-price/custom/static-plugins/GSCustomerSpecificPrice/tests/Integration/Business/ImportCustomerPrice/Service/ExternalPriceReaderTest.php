<?php declare(strict_types=1);

namespace GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\DTO\PriceDTO;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\ExternalPriceReader;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\ExternalPriceReaderInterface;
use GSCustomerSpecificPriceTest\TestBase;
use Shopware\Core\Framework\Context;

/**
 * Class ExternalPriceReaderTest
 * @package GSCustomerSpecificPriceTest\Integration\Business\ImportCustomerPrice\Service
 */
class ExternalPriceReaderTest extends TestBase
{
    /**
     * @var ExternalPriceReaderInterface
     */
    private ExternalPriceReaderInterface $priceReader;

    /**
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $context = Context::createDefaultContext();
        $this->createProduct($context);
        $this->priceReader = $this->getContainer()->get(ExternalPriceReader::class);
    }

    /**
     * @return void
     */
    public function testFetchPrices(): void
    {
        $pricesList = $this->priceReader->fetchPrices('customerNumber', self::PRODUCT_NUMBER);
        self::assertNotEmpty($pricesList[self::PRODUCT_NUMBER]);
        self::assertSame(
            get_class($pricesList[self::PRODUCT_NUMBER]),
            PriceDTO::class
        );
        self::assertGreaterThan(0, $pricesList[self::PRODUCT_NUMBER]->netValue);
        self::assertGreaterThan(0, $pricesList[self::PRODUCT_NUMBER]->grossValue);
        self::assertGreaterThan(0, $pricesList[self::PRODUCT_NUMBER]->listNetValue);
        self::assertGreaterThan(0, $pricesList[self::PRODUCT_NUMBER]->listGrossValue);
    }

    /**
     * @return void
     */
    public function testFetchPricesWithoutPriceSpecified(): void
    {
        $pricesList = $this->priceReader->fetchPrices('customerNumber');
        self::assertNotEmpty($pricesList);
    }
}
