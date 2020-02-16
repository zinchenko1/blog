<?php

namespace App\Controller\Rest;

use App\Entity\Post;
use Doctrine\Common\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @FOSRest\Get("/posts/{id}")
     * @param mixed $id
     */
    public function getPost($id)
    {
        if (!$id) {
            throw new HttpException(400, 'Invalid id');
        }
        $post = $this->registry->getRepository(Post::class)->find($id);

        if (!$post) {
            throw new HttpException(400, 'Invalid data');
        }

        return $this->createApiResponse(['data' => $post], ['groups' => 'post:show']);
    }

    /**
     * @FOSRest\Get("/posts")
     */
    public function getPosts()
    {
        $posts = $this->registry->getRepository(Post::class)->findAll();

        return $this->createApiResponse(['data' => $posts], ['groups' => 'post:show']);
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
