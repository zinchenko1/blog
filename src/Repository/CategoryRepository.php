<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return array
     */
    public function getRelatedPosts(): array
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('c, p')
            ->where('c.isMain = 0')
            ->from('App\Entity\Category', 'c')
            ->join('c.posts', 'p')
            ->setMaxResults(40 )
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }
}
