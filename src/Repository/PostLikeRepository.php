<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PostLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostLike[]    findAll()
 * @method PostLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostLike::class);
    }

    public function getLikesCountByPost(Post $post, bool $type)
    {
        return $this->createQueryBuilder('pl')
            ->select('COUNT(pl.id)')
            ->where('pl.post = :post')
            ->andWhere('pl.type = :type')
            ->setParameter('type', $type)
            ->setParameter('post', $post)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findPostLike(Post $post, string $ip, string $userAgent): ?PostLike
    {
        return $this->findOneBy([
            'post' => $post,
            'ip' => $ip,
            'userAgent' => $userAgent,
        ]);
    }
}
