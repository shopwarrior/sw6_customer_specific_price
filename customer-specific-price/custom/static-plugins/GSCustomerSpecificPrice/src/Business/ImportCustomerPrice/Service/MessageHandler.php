<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\MessageQueue\Message\ChangeCustomerPriceMessage;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerPriceServiceInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

/**
 * Class MessageHandler
 *
 * @package GSCustomerSpecificPrice\Business\ImportProductPrice\Service
 */
class MessageHandler implements MessageSubscriberInterface
{
    /**
     * @var CustomerPriceServiceInterface
     */
    private CustomerPriceServiceInterface $customerPriceService;

    /**
     * @param CustomerPriceServiceInterface $customerPriceService
     */
    public function __construct(CustomerPriceServiceInterface $customerPriceService)
    {
        $this->customerPriceService = $customerPriceService;
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        return [
            ChangeCustomerPriceMessage::class,
        ];
    }

    /**
     * @param ChangeCustomerPriceMessage $message
     */
    public function __invoke(ChangeCustomerPriceMessage $message): void
    {
        $this->customerPriceService->decreaseCustomerPrices($message->getCustomerId());
    }
}
