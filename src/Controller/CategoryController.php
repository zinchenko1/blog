<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_index")
     * @return Response
     */
    public function getCategories(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();

        return $this->render('/category/index.html.twig', [
            'categories' => $categories,
        ]);

    }

    /**
     * @Route("/category/{categorySlug}", name="category_posts", methods={"GET"})
     * @return Response
     * @ParamConverter("category", options={"mapping" : {"categorySlug" : "slug"}})
     */
    public function getCategoryPosts($categorySlug): Response
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->findOneBy(['slug' => $categorySlug]);

        return $this->render('/category/show.html.twig', [
            'categoryPosts' => $category,
        ]);

    }
}
