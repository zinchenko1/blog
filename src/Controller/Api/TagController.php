<?php

namespace App\Controller\Api;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class TagController extends ApiController
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
     * Return tag by ID.
     * @Route("/tags/{id<\d+>}", name="api_tag_show", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return Tag by ID",
     *     @Model(type=Tag::class, groups={"tag:show"})
     * )
     * @SWG\Tag(name="tags")
     * @Security(name="Bearer")
     * @param Tag $tag
     * @return response
     * @ParamConverter("tag", class="App:Tag")
     */
    public function getTag($tag): Response
    {
        return $this->createApiResponse(
            ['data' => $tag], ['groups' => ['tag:show']]
        );
    }

    /**
     * Delete tag by ID.
     * @Route("/tags/{id<\d+>}", name="api_tag_delete", methods={"DELETE"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Delete Tag by ID",
     * )
     * @SWG\Tag(name="tags")
     * @Security(name="Bearer")
     * @param Tag $tag
     * @ParamConverter("tag", class="App:Tag")
     * @return Response
     */
    public function deleteTag(Tag $tag): Response
    {
        $this->registry->getManager()->remove($tag);
        $this->registry->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Return all tags.
     * @Route("/tags", name="api_tags_list", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return all tags",
     *     @Model(type=Tag::class, groups={"tag:show"})
     * )
     * @SWG\Tag(name="tags")
     * @Security(name="Bearer")
     */
    public function getCategories(): Response
    {
        $tags = $this->registry->getRepository(Tag::class)->findAll();

        return $this->createApiResponse(
            ['data' => $tags], ['groups' => ['tag:show']]
        );
    }
}
