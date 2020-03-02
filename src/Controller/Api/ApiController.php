<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * PostController constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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
