<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\DTO;

/**
 * Class PriceDTO
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\DTO
 */
class PriceDTO
{
    /**
     * @var float
     */
    public float $netValue;

    /**
     * @var float
     */
    public float $grossValue;

    /**
     * @var float
     */
    public float $listNetValue;

    /**
     * @var float
     */
    public float $listGrossValue;

    /**
     * @param float $netValue
     * @param float $grossValue
     * @param float $listNetValue
     * @param float $listGrossValue
     */
    public function __construct(
        float $netValue,
        float $grossValue,
        float $listNetValue,
        float $listGrossValue
    ) {
        $this->netValue = $netValue;
        $this->grossValue = $grossValue;
        $this->listNetValue = $listNetValue;
        $this->listGrossValue = $listGrossValue;
    }
}
