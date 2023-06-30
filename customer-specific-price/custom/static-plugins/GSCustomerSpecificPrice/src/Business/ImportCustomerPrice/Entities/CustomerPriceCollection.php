<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Class CustomerPriceCollection
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities
 */
class CustomerPriceCollection extends EntityCollection
{
    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return CustomerPriceEntity::class;
    }
}
