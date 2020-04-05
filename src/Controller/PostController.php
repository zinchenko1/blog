<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\PostView;
use App\Form\CommentType;
use App\Repository\PostLikeRepository;
use App\Repository\PostViewRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ManagerRegistry;


class PostController extends AbstractController
{
    private $registry;
    private $postLikeRepository;
    private $postViewRepository;

    public function __construct(ManagerRegistry $registry, PostLikeRepository $postLikeRepository, PostViewRepository $postViewRepository)
    {
        $this->registry = $registry;
        $this->postLikeRepository = $postLikeRepository;
        $this->postViewRepository = $postViewRepository;
    }

    /**
     * @Route("/post/{postSlug}", name="post", methods={"GET"})
     * @param Post $post
     * @param Request $request
     * @return Response
     * @ParamConverter("post", options={"mapping" : {"postSlug" : "slug"}})
     */
    public function getPost(Post $post, Request $request): Response
    {
        $userAgent = $request->headers->get('user-agent');
        $this->incrementView($post, $request->getClientIp(), $userAgent);

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @ParamConverter("post", options={"mapping" : {"postSlug" : "slug"}})
     * @param Post $post
     * @return Response
     */
    public function postLikePlugin(Post $post): Response
    {
        $countLikes = $this->postLikeRepository->getLikesCountByPost($post, PostLike::TYPE_LIKE);
        $countDislikes = $this->postLikeRepository->getLikesCountByPost($post, PostLike::TYPE_DISLIKE);

        return $this->render('includes/likes.html.twig', [
            'postSlug' => $post->getSlug(),
            'countDislikes' => $countDislikes,
            'countLikes' => $countLikes,
        ]);
    }

    private function incrementView(Post $post, ?string $ip, ?string $userAgent): PostView
    {
        $postView = $this->postViewRepository->findByIpAndUserAgentAndPost($post, $ip, $userAgent);
        if (null === $postView) {
            $postView = new PostView();
            $postView
                ->setIp($ip)
                ->setUserAgent($userAgent)
                ->setPost($post);
            $this->registry->getManager()->persist($postView);
            $this->registry->getManager()->flush();
        }

        return $postView;
    }

    /**
     * @Route("/post/like/{postSlug}/{type}", name="post_like", methods={"GET"})
     * @ParamConverter("post", options={"mapping" : {"postSlug" : "slug"}})
     * @param Post $post
     * @param int $type
     * @param Request $request
     * @return RedirectResponse
     */
    public function like(Post $post, int $type, Request $request): RedirectResponse
    {
        $this->addLikeIfNotAlreadyLiked(
            $post,
            (bool) $type,
            $request->getClientIp(),
            $request->headers->get('user-agent')
        );
        $referer = $request->headers->get('referer');

        if (!is_string($referer) || !$referer) {
            return $this->redirectToRoute('post', ['postSlug' => $post->getSlug()]);
        }

        return $this->redirect($referer);
    }

    private function addLikeIfNotAlreadyLiked(Post $post, bool $type, string $ip, string $userAgent): PostLike
    {
        $postLike = $this->postLikeRepository->findPostLike($post, $ip, $userAgent);
        if ($postLike === null) {
            $postLike = new PostLike();
            $postLike
                ->setPost($post)
                ->setIp($ip)
                ->setUserAgent($userAgent)
            ;
            $this->registry->getManager()->persist($postLike);
        }
        $postLike->setType($type);
        $this->registry->getManager()->flush();

        return $postLike;
    }

    /**
     * @return Response
     */
    public function renderRecentPost(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $recentPosts = $repository->findBy(['status' => Post::STATUS_ACTIVE], ['id' => 'DESC'], 3);

        return $this->render('includes/recent-post.html.twig', [
            'recentPosts' => $recentPosts
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     *
     * @return Response
     * @ParamConverter("post", options={"mapping" : {"postSlug" : "slug"}})
     */
    public function commentNew(Request $request, Post $post): Response
    {
        $comment = new Comment();
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $form->setPost()
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post', [
                'postSlug' => $post->getSlug(), ]);
        }

        return $this->render('layouts/comment-form.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    public function commentForm(Post $post): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('layouts/comment-form.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

}
