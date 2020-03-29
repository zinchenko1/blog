<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site_index")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->findBy(['status' => Post::STATUS_ACTIVE]);

        return $this->render('/layouts/base.html.twig', [
            'posts' => $posts,
        ]);
    }

}
