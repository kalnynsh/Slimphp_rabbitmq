<?php

declare(strict_types=1);

namespace Api\Http\Action\Author\Video;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Api\Model\Video\UseCase\Video\Create\Command;
use Api\Model\Video\UseCase\Video\Create\Handler;
use Api\Http\Validator\Validator;
use Api\Http\Exception\ValidationException;

class CreateAction implements RequestHandlerInterface
{
    private $handler;
    private $validator;

    public function __construct(Handler $handler, Validator $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $command = $this->deserialize($request);

        if ($errors = $this->validator->validate($command)) {
            throw new ValidationException($errors);
        }

        $id = $this->handler->handle($command);

        return new JsonResponse([
            'id' => $id->getId(),
        ], 201);
    }

    private function deserialize(ServerRequestInterface $request): Command
    {
        $command = new Command();

        $command->author = $request->getAttribute('oauth_user_id');
        $command->file = $request->getUploadedFiles()['file'] ?? '';

        return $command;
    }
}
