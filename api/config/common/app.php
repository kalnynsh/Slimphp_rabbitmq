<?php

declare(strict_types=1);

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Api\Model;
use Api\Http\Validator\Validator;
use Api\Http\Middleware;
use Api\Http\Action;

return [
    ValidatorInterface::class => function () {
        AnnotationRegistry::registerLoader('class_exists');

        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    },

    Validator::class => function (ContainerInterface $container) {
        return new Validator(
            $container->get(ValidatorInterface::class)
        );
    },

    Middleware\DomainExceptionMiddleware::class => function () {
        return new Middleware\DomainExceptionMiddleware();
    },

    Middleware\ValidationExceptionMiddleware::class => function () {
        return new Middleware\ValidationExceptionMiddleware();
    },

    Action\HomeAction::class => function () {
        return new Action\HomeAction();
    },

    Action\Auth\SignUp\RequestAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\RequestAction(
            $container->get(Model\User\UseCase\SignUp\Request\Handler::class),
            $container->get(Validator::class)
        );
    },

    Action\Auth\SignUp\ConfirmAction::class => function (ContainerInterface $container) {
        return new Action\Auth\SignUp\ConfirmAction(
            $container->get(Model\User\UseCase\SignUp\Confirm\Handler::class),
            $container->get(Validator::class)
        );
    },
];
