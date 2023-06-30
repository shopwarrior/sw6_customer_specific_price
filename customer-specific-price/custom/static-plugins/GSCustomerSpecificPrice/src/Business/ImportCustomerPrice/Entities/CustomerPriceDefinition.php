<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Checkout\Customer\CustomerDefinition;

/**
 * Class Definition
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities
 */
class CustomerPriceDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'gs_customer_price';

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return CustomerPriceCollection::class;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return CustomerPriceEntity::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new Required(), new PrimaryKey()),
            (new FkField(
                'product_id',
                'productId',
                ProductDefinition::class
            ))->addFlags(new ApiAware(), new Required()),
            (new FkField(
                'customer_id',
                'customerId',
                CustomerDefinition::class
            ))->addFlags(new ApiAware(), new Required()),

            new FloatField('net_value', 'netValue'),
            new FloatField('gross_value', 'grossValue'),
            new FloatField('list_net_value', 'listNetValue'),
            new FloatField('list_gross_value', 'listGrossValue'),

            new CreatedAtField(),
            new UpdatedAtField(),
        ]);
    }
}
