<?php

namespace App\Controller;

use App\DTO\SearchDTO;
use App\Entity\Post;
use App\Form\SearchType;
use App\Repository\PostRepository;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private $knpPaginator;
    private $postRepository;

    /**
     * SearchController constructor.
     * @param PaginatorInterface $knpPaginator
     * @param PostRepository $postRepository
     */
    public function __construct(PaginatorInterface $knpPaginator, PostRepository $postRepository)
    {
        $this->knpPaginator = $knpPaginator;
        $this->postRepository = $postRepository;
    }

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
        $limit = $this->postRepository->getCountActivePosts();

        if ($query !== null) {
            $posts = $repositoryManager
                ->getRepository(Post::class)
                ->find($query, $limit)
            ;
            $paginatedPosts = $this->knpPaginator->paginate(
                $posts,
                $request->query->getInt('page', 1), 5
            );
        }

        return $this->render('post/search.html.twig', [
            'query' => $query,
            'posts' => $paginatedPosts,
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
