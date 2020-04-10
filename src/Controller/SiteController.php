<?php

namespace App\Controller;

use App\DTO\SearchDTO;
use App\Entity\Post;
use App\Repository\PostRepository;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SiteController extends AbstractController
{
    private $knpPaginator;
    private $postRepository;

    /**
     * SiteController constructor.
     * @param PaginatorInterface $knpPaginator
     * @param PostRepository $postRepository
     */
    public function __construct(PaginatorInterface $knpPaginator, PostRepository $postRepository)
    {
        $this->knpPaginator = $knpPaginator;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/", name="site_index")
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request, Breadcrumbs $breadcrumbs): Response
    {
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repository->findBy(['status' => Post::STATUS_ACTIVE]);

        $paginatedPosts = $this->knpPaginator->paginate(
            $posts,
            $request->query->getInt('page', 1), 5
        );

        return $this->render('/layouts/base.html.twig', [
            'posts' => $paginatedPosts,
        ]);
    }
}
