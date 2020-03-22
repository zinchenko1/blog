<?php

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Subscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscriber[]    findAll()
 * @method Subscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }

    /**
     * @param string $token
     * @return Subscriber|null
     */
    public function findByToken(string $token): ?Subscriber
    {
        return $this->findOneBy(['token' => $token]);
    }

    /**
     * @return Query
     */
    public function getActiveSubscribersQuery(): Query
    {
        return $this->createQueryBuilder('s')
            ->where('s.status = :status')
            ->setParameter('status', Subscriber::STATUS_APPROVED)
            ->getQuery()
        ;
    }
}
