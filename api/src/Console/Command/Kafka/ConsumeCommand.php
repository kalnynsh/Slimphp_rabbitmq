<?php

declare(strict_types=1);

namespace Api\Console\Command\Kafka;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;
use Psr\Log\LoggerInterface;
use Kafka\ConsumerConfig;
use Kafka\Consumer;

class ConsumeCommand extends Command
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
        $this->setName('kafka:demo:consume');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Consume messages</comment>');

        $config = ConsumerConfig::getInstance();
        $config->setMetadataRefreshIntervalMs(10000);
        $config->setMetadataBrokerList($this->brokers);
        $config->setBrokerVersion('1.1.0');
        $config->setGroupId('demo');
        $config->setTopics(['notifications']);
        $config->setOffsetReset('earliest');

        $consumer = new Consumer();
        $consumer->setLogger($this->logger);

        $consumer->start(function ($topic, $part, $message) use ($output) {
            $output->writeln(print_r($message, true));
        });

        $output->writeln('<info>Done!</info>');
    }
}
