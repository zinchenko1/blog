<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PostView|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostView|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostView[]    findAll()
 * @method PostView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostView::class);
    }

    /**
     * @param Post $post
     * @param string $ip
     * @param string $userAgent
     * @return PostView|null
     */
    public function findByIpAndUserAgentAndPost(Post $post, string $ip, string $userAgent): ?PostView
    {
        return $this->findOneBy([
            'post' => $post,
            'ip' => $ip,
            'userAgent' => $userAgent,
        ]);
    }
}
