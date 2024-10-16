<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByUserEmail($email)
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findPaginated(int $currentPage = 1, int $limit = 10)
    {
        $query = $this->createQueryBuilder('user')
            ->orderBy('user.id', 'ASC')
            ->getQuery()
            ->setFirstResult(($currentPage - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($query, true);
    }
}
