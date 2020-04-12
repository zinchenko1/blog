<?php

namespace App\Controller;

use App\Entity\User\Author;
use App\Entity\User\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class AuthorController extends AbstractController
{
    /**
     * @Route("/authors", name="authors_index")
     * @return Response
     */
    public function getAuthors(Breadcrumbs $breadcrumbs): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $authors = $repository->findByRole(User::ROLES['author']);

        $breadcrumbs->prependRouteItem("Home", "site_index");
        $breadcrumbs->addItem("Authors");

        return $this->render('/author/authors.html.twig', [
            'authors' => $authors
        ]);
    }

    /**
     * @Route("/author/{authorId}", name="author_posts", methods={"GET"})
     * @return Response
     * @ParamConverter("author", options={"mapping" : {"authorId" : "slug"}})
     */
    public function getAuthorPosts($authorId, Breadcrumbs $breadcrumbs): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $author = $repository->findOneBy(['id' => $authorId, 'status' => User::STATUS_ACTIVE]);

        $breadcrumbs->prependRouteItem("Home", "site_index");
        $breadcrumbs->addRouteItem("Authors", "authors_index");
        $breadcrumbs->addItem($author->getFirstName() . " " . $author->getLastName());

        return $this->render('/author/show-post.html.twig', [
            'author' => $author,
        ]);
    }
}
