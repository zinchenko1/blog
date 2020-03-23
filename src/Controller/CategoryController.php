<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
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
            'categoryPosts' => $category
        ]);

    }
}
