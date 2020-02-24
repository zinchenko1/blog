<?php

namespace App\Controller\Api;

use App\Entity\Post;
use Doctrine\Common\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class PostController extends AbstractFOSRestController
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * PostController constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry, SerializerInterface $serializer)
    {
        $this->registry = $registry;
        $this->serializer = $serializer;
    }

    /**
     * Return post by ID.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return Post by ID",
     *     @Model(type=Post::class, groups={"post:show"})
     * )
     * @SWG\Tag(name="posts")
     * @Security(name="Bearer")
     *
     * @FOSRest\Get("/posts/{id<\d+>}")
     * @param Post $post
     * @return response
     * @ParamConverter("post", class="App:Post")
     */
    public function getPost($post): Response
    {
        return $this->createApiResponse([
            'data' => $post], ['groups' => ['post:show']
        ]);
    }

    /**
     * Delete post by ID.
     *
     * @SWG\Response(
     *     response=204,
     *     description="Delete Post by ID",
     * )
     * @SWG\Tag(name="posts")
     * @Security(name="Bearer")
     *
     * @FOSRest\Delete("/posts/{id<\d+>}")
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
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return all posts",
     *     @Model(type=Post::class, groups={"post:show"})
     * )
     * @SWG\Tag(name="posts")
     * @Security(name="Bearer")
     *
     * @FOSRest\Get("/posts")
     */
    public function getPosts(): Response
    {
        $posts = $this->registry->getRepository(Post::class)->findAll();

        return $this->createApiResponse([
            'data' => $posts], ['groups' => ['post:show']
        ]);
    }

    protected function createApiResponse($data, array $context = [], $statusCode = 200): Response
    {
        $json = $this->serialize($data, $context);
        return new Response(
            $json, $statusCode, [
                'Content-Type' => 'application/json',
            ]
        );
    }

    protected function serialize($data, $context, $format = 'json'): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }
}
