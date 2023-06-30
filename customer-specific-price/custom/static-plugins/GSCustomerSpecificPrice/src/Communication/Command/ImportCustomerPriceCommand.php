<?php declare(strict_types=1);

namespace GSCustomerSpecificPrice\Communication\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GSCustomerSpecificPrice\Business\ImportCustomerPrice\FacadeInterface;
use GSCustomerSpecificPrice\Persistence\GSCustomerSpecificPriceConstants;

/**
 * Class ImportCustomerPriceCommand
 *
 * @package GSCustomerSpecificPrice\Communication\Command
 */
class ImportCustomerPriceCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'gs:import-customer-command';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Fetch customer specific price from API and import it into shopware.';

    /**
     * @var FacadeInterface
     */
    private FacadeInterface $facade;

    /**
     * ImportCustomerPriceCommand constructor.
     * @param FacadeInterface $facade
     */
    public function __construct(FacadeInterface $facade)
    {
        parent::__construct();
        $this->facade = $facade;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument(
                'customerNumber',
                InputArgument::REQUIRED,
                'Specify customerNumber for which product prices should be imported.'
            )
            ->addArgument(
                'productNumber',
                InputArgument::OPTIONAL,
                'Specify productNumber for which prices should be imported.'
            )
            ->setDescription(self::$defaultDescription);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start price import ' . date('Y.m.d H:i:s'));
        switch ($this->facade->import(
            $input->getArgument('customerNumber'),
            $input->getArgument('productNumber')
        )
        ) {
            case GSCustomerSpecificPriceConstants::CUSTOMER_NOT_EXISTS:
                $output->writeln(
                    "Customer with customerNumber `{$input->getArgument('customerNumber')}` doesn't exists."
                );
                break;
            case GSCustomerSpecificPriceConstants::ARTICLE_NOT_EXISTS:
                $output->writeln(
                    "Product with productNumber `{$input->getArgument('productNumber')}` doesn't exists."
                );
                break;
            case GSCustomerSpecificPriceConstants::NO_PRICES_FETCHED:
                $output->writeln('No prices fetched.');
                break;
            case GSCustomerSpecificPriceConstants::SUCCESS:
                $output->writeln('Import sucessfully done!');
        }
        $output->writeln('Import has been finished '.date('Y.m.d H:i:s'));

        return self::SUCCESS;
    }
}
