<?php

namespace FusionLab\Core\Console\Command;

use Magento\Framework\App\State;
use FusionLab\Core\Model\Registration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegistrationCommand extends Command
{
    /**
     * @var Registration
     */
    private $_Registration;

    /**
     * @var State
     */
    private $_state;

    /**
     * SaveConfigCommand constructor.
     * @param Registration $Registration
     * @param State $state
     */
    public function __construct(Registration $Registration, State $state)
    {
        $this->_Registration = $Registration;
        $this->_state = $state;
        parent::__construct();
    }

    /**
     * Configure the CLI command
     */
    protected function configure()
    {
        $this->setName('fusionlab:register')
            ->setDescription('init registration process');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // Set area code to adminhtml to allow config writes
            $this->_state->setAreaCode('adminhtml');
            $this->_Registration->register();
            return Command::SUCCESS;
        } catch (\Exception $e) {
            return Command::FAILURE;
        }
    }
}
