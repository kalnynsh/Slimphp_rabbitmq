<?php

declare(strict_types=1);

namespace Api\Http\Action\Auth\SignUp;

use Zend\Diactoros\Response\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Api\Model\User\UseCase\SignUp\Request\Handler;
use Api\Model\User\UseCase\SignUp\Request\Command;

class RequestAction implements RequestHandlerInterface
{
    private $handler;
    private $validator;

    public function __construct(
        Handler $handler,
        ValidatorInterface $validator
    ) {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $command = new Command();

        $command->email = $body['email'] ?? '';
        $command->password = $body['password'] ?? '';

        $violations = $this->validator->validate($command);

        if ($violations->count() > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] =
                    $violation->getMessage();
            }

            return new JsonResponse(['errors' => $errors], 400);
        }

        $this->handler->handle($command);

        return new JsonResponse([
            'email' => $command->email,
        ], 201);
    }
}
