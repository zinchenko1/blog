<?php

namespace App\Service;

use App\Entity\Subscriber;
use Swift_Mailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class NotificationSender
{
    private $mailer;
    private $twig;
    private $router;

    public function __construct(Swift_Mailer $mailer, Environment $twig, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
    }

    public function sendJoinSubscriptionMessage(Subscriber $subscriber)
    {
        $message = (new \Swift_Message('Confirm your Blog email subscription'))
            ->setFrom('no-reply@'. $_ENV['HOSTNAME_URL'])
            ->setTo($subscriber->getEmail())
            ->setBody(
                $this->twig->render(
                    'email/subscriber-verify.html.twig',
                    [
                        'email' => $subscriber->getEmail(),
                        'url' => $this->router->generate('subscriber_confirm', [
                            'token' =>  $subscriber->getToken(),
                        ], UrlGeneratorInterface::ABSOLUTE_URL)
                    ]
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}
