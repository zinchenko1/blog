<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PostController extends AbstractController
{
    /**
     * @Route("/post/{postSlug}", name="post", methods={"GET"})
     * @return Response
     * @ParamConverter("postSlug", options={"mapping" : {"postSlug" : "slug"}})
     */
    public function getPosts($postSlug, Request $request): Response
    {
        /**
         * @var string|null $userAgent
         */
        $userAgent = $request->headers->get('user-agent');

        $repository = $this->getDoctrine()->getRepository(Post::class);
        $post = $repository->findOneBy(['slug' => $postSlug]);

        $this->incrementView($post, $request->getClientIp(), $userAgent);

        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    private function incrementView(Post $post, ?string $ip, ?string $userAgent): PostView
    {
        $postView = $this
            ->getDoctrine()
            ->getRepository(PostView::class)
            ->findByIpAndUserAgentAndPost($post, $ip, $userAgent)
        ;

        if ($postView === null) {
            $postView = new PostView();

            $postView
                ->setIp($ip)
                ->setUserAgent($userAgent)
                ->setPost($post)
            ;

            $em = $this
                ->getDoctrine()
                ->getManager()
            ;

            $em->persist($postView);
            $em->flush();
        }

        return $postView;
    }
}
