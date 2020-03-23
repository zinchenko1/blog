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
        $posts = $repository->findAll();

        return $this->render('/layouts/base.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/rand", name="rand_post")
     */
    public function randArticle(Request $request): Response
    {
        /**
         * @var string|null $userAgent
         */
        $userAgent = $request->headers->get('user-agent');

        $repository = $this->getDoctrine()->getRepository(Post::class);
        $article = $repository->findOneBy(['status' => Post::STATUS_ACTIVE]);

        $this->incrementView($article, $request->getClientIp(), $userAgent);

        return $this->render('/layouts/base.html.twig');
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
