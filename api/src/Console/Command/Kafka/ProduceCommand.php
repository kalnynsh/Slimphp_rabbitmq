<?php

declare(strict_types=1);

namespace Api\Console\Command\Kafka;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Psr\Log\LoggerInterface;
use Kafka\Producer;

class ProduceCommand extends Command
{
    private $logger;
    private $config;

    public function __construct(
        LoggerInterface $logger,
        ProducerConfig $config
    ) {
        $this->logger = $logger;
        $this->config = $config;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('kafka:demo:produce')
            ->addArgument('user_id', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Produce message</comment>');

        $producer = new Producer();
        $producer->setLogger($this->logger);

        $producer->send([
            [
                'topic' => 'notifications',
                'value' => json_encode([
                    'type' => 'notification',
                    'user_id' => $input->getArgument('user_id'),
                    'message' => 'Hello from Kafka!',
                ]),
                'key' => '',
            ],
        ]);

        $output->writeln('<info>Done!</info>');
    }
}
