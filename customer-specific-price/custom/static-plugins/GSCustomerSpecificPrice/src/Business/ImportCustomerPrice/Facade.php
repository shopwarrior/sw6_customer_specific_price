<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\InputValidationInterface;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\ExternalPriceReaderInterface;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerPriceServiceInterface;
use GSCustomerSpecificPrice\Persistence\GSCustomerSpecificPriceConstants;

/**
 * Class Facade
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice
 */
class Facade implements FacadeInterface
{
    /**
     * @var CustomerPriceServiceInterface
     */
    private CustomerPriceServiceInterface $customerPriceService;

    /**
     * @var InputValidationInterface
     */
    private InputValidationInterface $inputValidation;

    /**
     * @var ExternalPriceReaderInterface
     */
    private ExternalPriceReaderInterface $priceReader;

    /**
     * @param CustomerPriceServiceInterface $customerPriceService
     * @param ExternalPriceReaderInterface $priceReader
     * @param InputValidationInterface $inputValidation
     */
    public function __construct(
        CustomerPriceServiceInterface $customerPriceService,
        ExternalPriceReaderInterface $priceReader,
        InputValidationInterface $inputValidation
    ) {
        $this->customerPriceService = $customerPriceService;
        $this->inputValidation = $inputValidation;
        $this->priceReader = $priceReader;
    }

    /**
     * @param string $customerNumber
     * @param string|null $productNumber
     * @return int
     */
    public function import(string $customerNumber, ?string $productNumber = null): int
    {
        if (!$this->inputValidation->isCustomerExists($customerNumber)) {
            return GSCustomerSpecificPriceConstants::CUSTOMER_NOT_EXISTS;
        }
        if (!is_null($productNumber) && !$this->inputValidation->isProductExists($productNumber)) {
            return GSCustomerSpecificPriceConstants::ARTICLE_NOT_EXISTS;
        }

        $prices = $this->priceReader->fetchPrices($customerNumber, $productNumber);
        if ($prices === []) {
            return GSCustomerSpecificPriceConstants::NO_PRICES_FETCHED;
        }
        $this->customerPriceService->createFromFetchedPrices($customerNumber, $prices);

        return GSCustomerSpecificPriceConstants::SUCCESS;
    }
}
