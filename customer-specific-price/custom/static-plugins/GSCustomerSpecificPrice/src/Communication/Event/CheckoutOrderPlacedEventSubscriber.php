<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Communication\Event;

use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\MessageSenderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CheckoutOrderPlacedEventSubscriber
 *
 * @package GSCustomerSpecificPrice\Communication\Event
 */
class CheckoutOrderPlacedEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var MessageSenderInterface
     */
    private MessageSenderInterface $messageSender;

    /**
     * @param MessageSenderInterface $messageSender
     */
    public function __construct(MessageSenderInterface $messageSender)
    {
        $this->messageSender = $messageSender;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutOrderPlacedEvent::class => 'onCheckoutPlaced',
        ];
    }

    /**
     * @param CheckoutOrderPlacedEvent $event
     * @return void
     */
    public function onCheckoutPlaced(CheckoutOrderPlacedEvent $event): void
    {
        $this->messageSender->sendMessage($event->getOrder()->getOrderCustomer()->getCustomerId());
    }
}
