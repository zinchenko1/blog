<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\PostView;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Repository\PostLikeRepository;
use App\Repository\PostViewRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ManagerRegistry;


class ContactController extends AbstractController
{
    /**
     * @Route("/contacts", name="contacts")
     * @param Request $request
     *
     * @return Response
     */
    public function contacts(Request $request): Response
    {
        return $this->render('/contacts/contacts.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function contactNew(Request $request): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contacts');
        }

        return $this->render('/contacts/contacts.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function contactForm(): Response
    {
        $form = $this->createForm(ContactType::class);

        return $this->render('contacts/contact-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
