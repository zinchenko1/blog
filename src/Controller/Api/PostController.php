<?php

namespace App\Controller\Api;

use App\Entity\Post;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class PostController extends ApiController
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * PostController constructor.
     * @param ManagerRegistry $registry
     * @param SerializerInterface $serializer
     */
    public function __construct(ManagerRegistry $registry, SerializerInterface $serializer)
    {
        parent::__construct($serializer);
        $this->registry = $registry;
    }

    /**
     * Return post by ID.
     * @Route("/posts/{id<\d+>}", name="api_post_show", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return Post by ID",
     *     @Model(type=Post::class, groups={"post:show"})
     * )
     * @SWG\Tag(name="posts")
     * @Security(name="Bearer")
     *
     * @param Post $post
     * @return response
     * @ParamConverter("post", class="App:Post")
     */
    public function getPost($post): Response
    {
        return $this->createApiResponse(
            ['data' => $post], ['groups' => ['post:show']]
        );
    }

    /**
     * Delete post by ID.
     * @Route("/posts/{id<\d+>}", name="api_post_delete", methods={"DELETE"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Delete Post by ID",
     * )
     * @SWG\Tag(name="posts")
     * @Security(name="Bearer")
     * @param Post $post
     * @ParamConverter("post", class="App:Post")
     * @return Response
     */
    public function deletePost(Post $post): Response
    {
        $post->getTags()->clear();
        $post->getComments()->clear();
        $this->registry->getManager()->remove($post);
        $this->registry->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Return all posts.
     * @Route("/posts", name="api_posts_list", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return all posts",
     *     @Model(type=Post::class, groups={"post:show"})
     * )
     * @SWG\Tag(name="posts")
     * @Security(name="Bearer")
     */
    public function getPosts(): Response
    {
        $posts = $this->registry->getRepository(Post::class)->findAll();

        return $this->createApiResponse(
            ['data' => $posts], ['groups' => ['post:show']]
        );
    }

    /**
     * Return List Comments.
     *
     * @Route("/{post}/comments", name="api_post_comments", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * ),
     * @SWG\Tag(name="posts")
     * @Security(name="Bearer")
     * @param Post $post
     * @throws HttpException
     * @return Response
     * @ParamConverter("post", class="App:Post")
     */
    public function getComments(Post $post): Response
    {
        $comments = $post->getComments();
        if (!$comments) {
            throw new HttpException(400, 'Comments not found');
        }

        return $this->createApiResponse(['data' => $comments], ['groups' => ['comment:show']]);
    }

    /**
     * Return List Tags.
     *
     * @Route("/{post}/tags", name="api_post_tag", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Return Tags list by Post ID",
     * )
     * @SWG\Tag(name="posts")
     * @Security(name="Bearer")
     * @param Post $post
     * @throws HttpException
     * @return Response
     * @ParamConverter("post", class="App:Post")
     */
    public function getTags(Post $post): Response
    {
        $tags = $post->getTags();
        if (!$tags) {
            throw new HttpException(400, 'Tags not found');
        }

        return $this->createApiResponse(['data' => $tags], ['groups' => ['tag:show']]);
    }
}
