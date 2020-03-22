<?php

namespace App\Controller;

use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PostController extends AbstractController
{
    /**
     * @Route("/post/{postSlug}", name="post", methods={"GET"})
     * @return Response
     * @ParamConverter("postSlug", options={"mapping" : {"postSlug" : "slug"}})
     */
    public function getPosts($postSlug): Response
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $post = $repository->findOneBy(['slug' => $postSlug]);

        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }
}
