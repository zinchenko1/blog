<?php

namespace App\Controller\Api;

use App\Entity\Category;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class CategoryController extends ApiController
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
     * Return category by ID.
     * @Route("/categories/{id<\d+>}", name="api_category_show", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return Category by ID",
     *     @Model(type=Category::class, groups={"category:show"})
     * )
     * @SWG\Tag(name="categories")
     * @Security(name="Bearer")
     * @param Category $category
     * @return response
     * @ParamConverter("pagegory", class="App:Category")
     */
    public function getCategory($category): Response
    {
        return $this->createApiResponse(
            ['data' => $category], ['groups' => ['category:show']]
        );
    }

    /**
     * Delete category by ID.
     * @Route("/categories/{id<\d+>}", name="api_category_delete", methods={"DELETE"})
     *
     * @SWG\Response(
     *     response=204,
     *     description="Delete Category by ID",
     * )
     * @SWG\Tag(name="categories")
     * @Security(name="Bearer")
     * @param Category $category
     * @ParamConverter("category", class="App:Category")
     * @return Response
     */
    public function deleteCategory(Category $category): Response
    {
        $this->registry->getManager()->remove($category);
        $this->registry->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Return all categories.
     * @Route("/categories", name="api_categories_list", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return all categories",
     *     @Model(type=Category::class, groups={"category:show"})
     * )
     * @SWG\Tag(name="categories")
     * @Security(name="Bearer")
     *
     */
    public function getCategories(): Response
    {
        $categories = $this->registry->getRepository(Category::class)->findAll();

        return $this->createApiResponse(
            ['data' => $categories], ['groups' => ['category:show']]
        );
    }
}
