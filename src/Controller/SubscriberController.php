<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Form\SubscriberType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\NotificationSender;

/**
 * @Route("/subscriber", name="subscriber_")
 */
class SubscriberController extends AbstractController
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @Route("/new", name="new", methods={"POST"})
     * @param Request $request
     * @param NotificationSender $notificationSender
     * @return Response
     */
    public function subscriberNew(Request $request, NotificationSender $notificationSender): Response
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscriber->setStatus(Subscriber::STATUS_UNVERIFIED);
            $subscriber->setToken();
            $em = $this->registry->getManager();
            $em->persist($subscriber);
            $em->flush();
            if ($subscriber->getEmail()) {
                $notificationSender->sendJoinSubscriptionMessage($subscriber);
            }
            $this->addFlash('success', 'You have successfully subscribed to the newsletter');

            return $this->redirectToRoute('site_index');
        }

        return $this->render('subscriber/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function subscriberForm(): Response
    {
        $form = $this->createForm(SubscriberType::class);

        return $this->render('subscriber/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm/{token}", name="confirm", requirements={"token"=".+"}, methods={"GET"})
     * @param $token
     * @return Response
     */
    public function subscriberConfirm($token): Response
    {
        $repository = $this->getDoctrine()->getRepository(Subscriber::class);
        if($subscriber = $repository->findOneBy(['token' => $token])) {
            /** @var Subscriber $subscriber */
            $subscriber->setStatus(Subscriber::STATUS_APPROVED);
            $em = $this->registry->getManager();
            $em->persist($subscriber);
            $em->flush();

            $this->addFlash('success', 'Subscription confirmed!');

            return $this->redirectToRoute('site_index');
        }
    }
}
