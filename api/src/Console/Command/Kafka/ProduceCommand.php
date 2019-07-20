<?php

declare(strict_types=1);

namespace Api\Console\Command\Kafka;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;
use Psr\Log\LoggerInterface;
use Kafka\ProducerConfig;
use Kafka\Producer;

class ProduceCommand extends Command
{
    private $logger;
    private $brokers;

    public function __construct(
        LoggerInterface $logger,
        string $brokers
    ) {
        $this->logger = $logger;
        $this->brokers = $brokers;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('kafka:demo:produce');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Produce message</comment>');

        $config = ProducerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList($this->brokers);
        $config->setBrokerVersion('1.1.0');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);

        $producer = new Producer();
        $producer->setLogger($this->logger);

        $producer->send([
            [
                'topic' => 'notifications',
                'value' => 'Hello from Kafka!',
                'key' => '',
            ]
        ]);

        $output->writeln('<info>Done!</info>');
    }
}
