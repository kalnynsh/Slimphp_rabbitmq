<?php

declare(strict_types=1);

namespace Api\Infrastructure\Framework\ErrorHandler;

use Slim\Handlers\Error;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LogHandler extends Error
{
    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        bool $displayErrorDetails = false
    ) {
        $this->logger = $logger;
        parent::__construct($displayErrorDetails);
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        \Exception $exception
    ) {
        $this->logger->error(
            $exception->getMessage(),
            [
                'exception' => $exception,
            ]
        );

        return parent::__invoke($request, $response, $exception);
    }
}
