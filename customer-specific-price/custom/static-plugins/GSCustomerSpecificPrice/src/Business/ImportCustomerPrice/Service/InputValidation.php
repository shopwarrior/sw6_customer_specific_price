<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use Psr\Log\LoggerInterface;

/**
 * Class InputValidation
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
class InputValidation implements InputValidationInterface
{
    /**
     * @var CustomerReaderInterface
     */
    private CustomerReaderInterface $customerReader;

    /**
     * @var ProductServiceInterface
     */
    private ProductServiceInterface $ProductService;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerReaderInterface $customerReader,
        ProductServiceInterface $ProductService
    ) {
        $this->customerReader = $customerReader;
        $this->ProductService = $ProductService;
    }

    /**
     * @param string $customerNumber
     * @return bool
     */
    public function isCustomerExists(string $customerNumber): bool
    {
        return $this->customerReader->isExists($customerNumber);
    }

    /**
     * @param string $productNumber
     * @return bool
     */
    public function isProductExists(string $productNumber): bool
    {
        return $this->ProductService->isExists($productNumber);
    }
}
