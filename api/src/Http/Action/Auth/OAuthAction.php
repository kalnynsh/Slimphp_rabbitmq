<?php

declare(strict_types=1);

namespace Api\Http\Action\Auth;

use Zend\Diactoros\Response;
use Psr\Log\LoggerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\AuthorizationServer;

class OAuthAction implements RequestHandlerInterface
{
    private $server;
    private $logger;

    public function __construct(
        AuthorizationServer $server,
        LoggerInterface $logger
    ) {
        $this->server = $server;
        $this->logger = $logger;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            return $this->server->respondToAccessTokenRequest(
                $request,
                new Response()
            );
        } catch (OAuthServerException $exception) {
            $this->logger->warning(
                $exception->getMessage(),
                ['exception' => $exception]
            );

            return $exception->generateHttpResponse(new Response());
        } catch (\Exception $exception) {
            $this->logger->warning(
                $exception->getMessage(),
                ['exception' => $exception]
            );

            return (new OAuthServerException(
                $exception->getMessage(),
                0,
                'unknown_error',
                500,
                'OAuth server unknown error'
            ))->generateHttpResponse(new Response());
        }
    }
}
