<?php

declare(strict_types=1);

namespace Api\Console\Command\Amqp;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ProduceCommand extends Command
{
    private $connection;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('ampq:demo:produce')
            ->addArgument('user_id', InputArgument::REQUIRED);
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $output->writeln('<comment>Produce messages</comment>');

        $exchange = 'notifications';
        $queue = 'messages';

        $connection = $this->connection;
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, false, false, false);
        $channel->exchange_declare($exchange, 'fanout', false, false, false);
        $channel->queue_bind($queue, $exchange);

        $data = [
            'type' => 'notification',
            'user_id' => $input->getArgument('user_id'),
            'message' => 'Hello from RabbitMQ!',
        ];

        $message = new AMQPMessage(
            json_encode($data),
            ['content_type' => 'text/plain']
        );

        $channel->basic_publish($message, $exchange);

        $channel->close();
        $connection->close();

        $output->writeln('<info>Done!</info>');
    }
}
