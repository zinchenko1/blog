<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ManagerRegistry;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;


class ContactController extends AbstractController
{
    /**
     * @Route("/contacts", name="contacts")
     * @param Request $request
     *
     * @return Response
     */
    public function contacts(Request $request, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->prependRouteItem("Home", "site_index");
        $breadcrumbs->addItem("Contacts");

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
