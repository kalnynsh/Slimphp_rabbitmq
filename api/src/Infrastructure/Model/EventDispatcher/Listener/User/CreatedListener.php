<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\EventDispatcher\Listener\User;

use Api\Model\User\Entity\User\Event\UserCreated;

class CreatedListener
{
    private $mailer;
    private $from;

    public function __construct(
        \Swift_Mailer $mailer,
        array $from
    ) {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function __invoke(UserCreated $vent)
    {
        $message = (new \Swift_Message('Signup confirmation'))
            ->setFrom($this->from)
            ->setTo($vent->email->getEmail())
            ->setBody(
                'Token: '
                . $vent->confirmToken->getToken()
            )
        ;

        if (!$this->mailer->send($message)) {
            throw new \RuntimeException('Unable to send message.');
        }
    }
}
