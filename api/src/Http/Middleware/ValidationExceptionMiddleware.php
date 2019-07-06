<?php

declare(strict_types=1);

namespace Api\Http\Middleware;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Api\Http\Exception\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (ValidationException $err) {
            return new JsonResponse([
                'errors' => $err->getErrors()->toArray(),
            ], 400);
        }
    }
}
