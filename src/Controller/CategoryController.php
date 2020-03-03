<?php

namespace App\Controller;

use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/{categorySlug}/posts", name="category_posts", methods={"GET"})
     * @param Category $category
     * @return Response
     * @ParamConverter("category", options={"mapping" : {"categorySlug" : "slug"}})
     */
    public function getCategoryPosts($category): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => [],
        ]);
    }
}
