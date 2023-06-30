<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\MessageQueue\Message;

/**
 * Class ChangeCustomerPriceMessage
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\MessageQueue\Message
 */
class ChangeCustomerPriceMessage
{
    /**
     * @var string
     */
    private string $customerId;

    /**
     * @param string $customerId
     */
    public function __construct(string $customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }
}
