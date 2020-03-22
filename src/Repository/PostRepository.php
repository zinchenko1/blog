<?php

namespace App\Repository;

use App\Entity\Post;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param DateTimeInterface $date
     * @param int $limit
     * @return array
     * @throws \Exception
     */
    public function getPopularByDate(DateTimeInterface $date, int $limit = Post::POPULAR_POSTS_COUNT): array
    {
        $to = new DateTime($date->format('Y-m-d') . ' 23:59:59');
        $from = new DateTime($date->format('Y-m-d') . ' 00:00:00');

        return $this->createQueryBuilder('p')
            ->leftJoin('p.postViews', 'pv')
            ->where('p.status = :status')
            ->andWhere('pv.date BETWEEN :from AND :to')
            ->setParameter('to', $to)
            ->setParameter('from', $from)
            ->setParameter('status', Post::STATUS_ACTIVE)
            ->groupBy('p.id')
            ->orderBy('COUNT(pv.id)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
