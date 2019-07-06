<?php

declare(strict_types=1);

namespace Api\Http\Action\Auth\SignUp;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Api\Model\User\UseCase\SignUp\Confirm\Handler;
use Api\Model\User\UseCase\SignUp\Confirm\Command;
use Api\Http\Validator\Validator;
use Api\Http\Exception\ValidationException;

class ConfirmAction implements RequestHandlerInterface
{
    private $handler;
    private $validator;

    public function __construct(
        Handler $handler,
        Validator $validator
    ) {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $command = new Command();

        $command->email = $body['email'] ?? '';
        $command->token = $body['token'] ?? '';

        if ($errors = $this->validator->validate($command)) {
            throw new ValidationException($errors);
        }

        $this->handler->handle($command);

        return new JsonResponse([]);
    }
}