<?php

namespace App\Service;

use App\Entity\Subscriber;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class NotificationSender
{
    private $mailer;
    private $twig;
    private $router;
    private $params;

    public function __construct(Swift_Mailer $mailer, Environment $twig, UrlGeneratorInterface $router, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
        $this->params = $params;
    }

    public function sendJoinSubscriptionMessage(Subscriber $subscriber)
    {
        $message = (new Swift_Message('Confirm your Blog email subscription'))
            ->setFrom('no-reply@'. $this->params->get('hostname_url'), $this->params->get('hostname_url'))
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

    public function sendSubscriberNotification($subscriber, $posts)
    {
        $message = (new Swift_Message('Daily popular posts'))
            ->setFrom('no-reply@'. $this->params->get('hostname_url'), $this->params->get('hostname_url'))
            ->setTo($subscriber->getEmail())
            ->setBody(
                $this->twig->render(
                    'email/subscriber-notification.html.twig',
                    [
                        'email' => $subscriber->getEmail(),
                        'posts' => $posts,
                        'url' => $this->router->generate('subscriber_unsubscribing', [
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
