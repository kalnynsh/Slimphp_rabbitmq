<?php

declare(strict_types=1);

namespace Api\Console\Command\Amqp;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use Api\Infrastructure\Amqp\AMQPHelper;

class ConsumeCommand extends Command
{
    private $connection;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('ampq:demo:consume');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $output->writeln('<comment>Consume messages</comment>');

        $connection = $this->connection;
        $channel = $connection->channel();
        $consumerTag = 'consumer_' . getmypid();

        AMQPHelper::initNotificatios($channel);

        AMQPHelper::registerShutdown(
            $connection,
            $channel
        );

        $channel->basic_consume(
            AMQPHelper::QUEUE_NOTIFICATIONS,
            $consumerTag,
            false,
            false,
            false,
            false,
            function ($message) use ($output) {
                $output->writeln(print_r(json_decode($message->body, true), true));

                /** @var AMPQChannel $channel */
                $channel = $message->delivery_info['channel'];
                $channel->basic_ack($message->delivery_info['delivery_tag']);
            }
        );

        while ((bool)\count($channel->callbacks)) {
            $channel->wait();
        }

        $output->writeln('<info>Done!</info>');
    }
}
