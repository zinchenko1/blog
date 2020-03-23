<?php

namespace App\Controller;

use App\DTO\SearchDTO;
use App\Entity\Post;
use App\Form\SearchType;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"GET"})
     * @param Request $request
     * @param RepositoryManagerInterface $repositoryManager
     *
     * @return Response
     */
    public function index(Request $request, RepositoryManagerInterface $repositoryManager): Response
    {
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        /**
         * @var SearchDTO
         */
        $searchData = $searchForm->getData();
        $query = $searchData->getQuery();
        $posts = [];
        if ($query !== null) {
            $posts = $repositoryManager
                ->getRepository(Post::class)
                ->find($query)
            ;
        }

        return $this->render('post/search.html.twig', [
            'query' => $query,
            'posts' => $posts,
        ]);
    }

    /**
     * @return Response
     */
    public function renderSearchForm(): Response
    {
        $searchForm = $this->createForm(SearchType::class);

        return $this->render('layouts/_search-form.html.twig', [
            'form' => $searchForm->createView(),
        ]);
    }
}
