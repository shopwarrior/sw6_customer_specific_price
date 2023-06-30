<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

/**
 * Interface MessageSenderInterface
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
interface MessageSenderInterface
{
    /**
     * @param $customerId
     * @return void
     */
    public function sendMessage($customerId): void;
}
