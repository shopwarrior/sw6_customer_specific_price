<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\MessageQueue\Message\ChangeCustomerPriceMessage;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class MessageSender
 *
 * @package GSCustomerSpecificPrice\Business\ImportProductPrice\Service
 */
class MessageSender implements MessageSenderInterface
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;

    /**
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param $customerId
     * @return void
     */
    public function sendMessage($customerId): void
    {
        $this->bus->dispatch(new ChangeCustomerPriceMessage($customerId));
    }
}
