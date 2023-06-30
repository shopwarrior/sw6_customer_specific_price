<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service;

use GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service\CustomerPriceServiceInterface;
use Shopware\Core\Content\Product\Aggregate\ProductPrice\ProductPriceCollection;
use Shopware\Core\Content\Product\SalesChannel\Price\AbstractProductPriceCalculator;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Pricing\Price;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

/**
 * Class CustomerPriceCalculator
 *
 * @package GSCustomerSpecificPrice\Business\ImportCustomerPrice\Service
 */
class CustomerPriceCalculator extends AbstractProductPriceCalculator
{
    /**
     * @var AbstractProductPriceCalculator
     */
    private AbstractProductPriceCalculator $decoratedService;

    /**
     * @var CustomerPriceServiceInterface
     */
    private CustomerPriceServiceInterface $customerPriceService;

    /**
     * @param AbstractProductPriceCalculator $productPriceCalculator
     * @param CustomerPriceServiceInterface $customerPriceService
     */
    public function __construct(
        AbstractProductPriceCalculator $productPriceCalculator,
        CustomerPriceServiceInterface $customerPriceService
    ) {
        $this->decoratedService = $productPriceCalculator;
        $this->customerPriceService = $customerPriceService;
    }

    /**
     * @return AbstractProductPriceCalculator
     */
    public function getDecorated(): AbstractProductPriceCalculator
    {
        return $this->decoratedService;
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->units = null;
    }

    /**
     * @param iterable $products
     * @param SalesChannelContext $context
     * @return void
     */
    public function calculate(iterable $products, SalesChannelContext $context): void
    {
        $productIds = [];
        array_walk($products, function (&$product) use (&$productIds) {
            /**
             * @var SalesChannelProductEntity $product
             */
            $productIds[] = $product->getId();
        });
        if ($context->getCustomerId()) {
            $personalPrices = $this->customerPriceService->getCustomerPersonalPrices(
                $context->getCustomerId(),
                $context->getContext(),
                $productIds
            );
            if (count($personalPrices) > 0) {
                /**
                 * @var SalesChannelProductEntity $product
                 */
                foreach ($products as $product) {
                    $product->setPrices(new ProductPriceCollection());
                    $product->setCheapestPrice(null);
                    if (isset($personalPrices[$product->getId()])) {
                        foreach ($product->getPrice() as $price) {
                            $price->setNet($personalPrices[$product->getId()]->getNetValue());
                            $price->setGross($personalPrices[$product->getId()]->getGrossValue());
                            if (!($price->getListPrice() instanceof Price)) {
                                $_price = clone $price;
                                $price->setListPrice($_price);
                            }
                            $price->getListPrice()->setNet($personalPrices[$product->getId()]->getListNetValue());
                            $price->getListPrice()->setGross($personalPrices[$product->getId()]->getListGrossValue());
                        }
                    }
                }
            }
        }
        $this->getDecorated()->calculate($products, $context);
    }
}
