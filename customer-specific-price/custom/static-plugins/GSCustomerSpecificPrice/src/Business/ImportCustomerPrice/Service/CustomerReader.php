<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use Psr\Log\LoggerInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use GSCustomerSpecificPrice\Persistence\Repository\CustomerRepositoryInterface;

/**
 * Class CustomerReader
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var SystemConfigService
     */
    private SystemConfigService $systemConfigService;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param SystemConfigService $systemConfigService
     * @param CustomerRepositoryInterface $customerRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        SystemConfigService $systemConfigService,
        CustomerRepositoryInterface $customerRepository,
        LoggerInterface $logger
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
    }

    /**
     * @param string $customerNumber
     * @return bool
     */
    public function isExists(string $customerNumber): bool
    {
        $customerId = $this->getCustomerIdByCustomerNumber($customerNumber);
        if ($this->systemConfigService->getBool('GSCustomerSpecificPrice.config.logDebug')) {
            $this->logger->info(
                is_null($customerId) ?
                "Sucessfully fetched customer with customerNumber {$customerNumber}":
                "Customer with customerNumber {$customerNumber} doesn't exist"
            );
        }

        return !is_null($customerId);
    }

    /**
     * @param string $customerNumber
     * @return string|null
     */
    public function getCustomerIdByCustomerNumber(string $customerNumber): ?string
    {
        return $this->customerRepository->getCustomerIdByCustomerNumber($customerNumber);
    }
}
