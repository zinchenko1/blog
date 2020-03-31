<?php

namespace App\Controller;

use App\Entity\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag", name="tag_index")
     * @return Response
     */
    public function getTags(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Tag::class);
        $tags = $repository->findAll();

        return $this->render('/tag/show.html.twig', [
            'tags' => $tags,
        ]);

    }

    /**
     * @Route("/tag/{tagSlug}", name="tag_posts", methods={"GET"})
     * @return Response
     * @ParamConverter("tag", options={"mapping" : {"tagSlug" : "slug"}})
     */
    public function getTagPosts($tagSlug): Response
    {
        $repository = $this->getDoctrine()->getRepository(Tag::class);
        $tagPosts = $repository->findOneBy(['slug' => $tagSlug]);

        return $this->render('/tag/show-post.html.twig', [
            'tagPosts' => $tagPosts,
        ]);

    }
}
