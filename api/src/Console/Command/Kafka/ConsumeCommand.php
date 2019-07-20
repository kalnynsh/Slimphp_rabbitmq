<?php

declare(strict_types=1);

namespace Api\Console\Command\Kafka;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;
use Psr\Log\LoggerInterface;
use Kafka\Consumer;

class ConsumeCommand extends Command
{
    private $logger;
    private $config;

    public function __construct(
        LoggerInterface $logger,
        ConsumerConfig $config
    ) {
        $this->logger = $logger;
        $this->config = $config;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('kafka:demo:consume');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Consume messages</comment>');

        $this->config->setGroupId('demo');
        $this->config->setTopics(['notifications']);
        $this->config->setOffsetReset('earliest');

        $consumer = new Consumer();
        $consumer->setLogger($this->logger);

        $consumer->start(function ($topic, $part, $message) use ($output) {
            $output->writeln(print_r($message, true));
        });

        $output->writeln('<info>Done!</info>');
    }
}
