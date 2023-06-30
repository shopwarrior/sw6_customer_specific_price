<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

/**
 * Class CustomerPriceEntity
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Entities
 */
class CustomerPriceEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string|null $productId
     */
    protected ?string $productId;

    /**
     * @var string|null $customerId
     */
    protected ?string $customerId;

    /**
     * @var float
     */
    protected float $netValue;

    /**
     * @var float
     */
    protected float $grossValue;

    /**
     * @var float
     */
    protected float $listNetValue;

    /**
     * @var float
     */
    protected float $listGrossValue;

    /**
     * @return string|null
     */
    public function getProductId(): ?string
    {
        return $this->productId;
    }

    /**
     * @param string|null $productId
     */
    public function setProductId(?string $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return string|null
     */
    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    /**
     * @param string|null $customerId
     */
    public function setCustomerId(?string $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * @return float
     */
    public function getNetValue(): float
    {
        return $this->netValue;
    }

    /**
     * @param float $netValue
     */
    public function setNetValue(float $netValue): void
    {
        $this->netValue = $netValue;
    }

    /**
     * @return float
     */
    public function getGrossValue(): float
    {
        return $this->grossValue;
    }

    /**
     * @param float $grossValue
     */
    public function setGrossValue(float $grossValue): void
    {
        $this->grossValue = $grossValue;
    }

    /**
     * @return float
     */
    public function getListNetValue(): float
    {
        return $this->listNetValue;
    }

    /**
     * @param float $listNetValue
     */
    public function setListNetValue(float $listNetValue): void
    {
        $this->listNetValue = $listNetValue;
    }

    /**
     * @return float
     */
    public function getListGrossValue(): float
    {
        return $this->listGrossValue;
    }

    /**
     * @param float $listGrossValue
     */
    public function setListGrossValue(float $listGrossValue): void
    {
        $this->listGrossValue = $listGrossValue;
    }
}
