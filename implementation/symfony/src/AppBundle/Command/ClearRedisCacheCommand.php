<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearRedisCacheCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:clear-cache');
        $this->addArgument('key', InputArgument::REQUIRED, 'Cache key to be cleared');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheEngine = $this->getContainer()->get('cache.app');
        $key = $input->getArgument('key');
        $cacheEngine->deleteItem($key);
    }

}