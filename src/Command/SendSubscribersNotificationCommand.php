<?php

namespace App\Command;

use App\Repository\PostRepository;
use App\Repository\SubscriberRepository;
use App\Service\NotificationSender;
use DateTime;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendSubscribersNotificationCommand extends Command
{
    protected static $defaultName = 'send:subscribers:notification';

    private $notificationSender;
    private $postRepository;
    private $subscriberRepository;

    public function __construct(NotificationSender $notificationSender, PostRepository $postRepository, SubscriberRepository $subscriberRepository)
    {
        parent::__construct();
        $this->notificationSender = $notificationSender;
        $this->postRepository = $postRepository;
        $this->subscriberRepository = $subscriberRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Send subscribers notification.')
            ->setHelp('This command allows you to send notifications...')
            ->addArgument('delay-limit', InputArgument::OPTIONAL, 'Delay between recipients (s)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $delay = $input->getArgument('delay-limit');
        $popularPostsForToday = $this
            ->postRepository
            ->getPopularByDate(new DateTime())
        ;

        if (\count($popularPostsForToday) === 0) {
            $io->warning('Today there are no posts views!');

            return;
        }

        $activeSubscribers = $this
            ->subscriberRepository
            ->getActiveSubscribersQuery()
            ->iterate()
        ;
        $io->note('Start sending!');

        foreach ($activeSubscribers as $subscriber) {
            if ($delay) {
                sleep($delay);
            } else {
                sleep(1);
            }
            $this
                ->notificationSender
                ->sendSubscriberNotification($subscriber[0], $popularPostsForToday)
            ;
            $io->note(date('Y-m-d H:i:s') . ': Send to ' . $subscriber[0]->getEmail());
        }

        $io->success('Finish sending!');
    }
}
