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
        // Строим запрос с использованием QueryBuilder
        $queryBuilder = $this->createQueryBuilder('user')
            ->select('user.id, user.name, user.email, user.created_at')
            ->orderBy('user.id', 'ASC')
            ->setFirstResult(($currentPage - 1) * $limit)
            ->setMaxResults($limit);


        $query = $queryBuilder->getQuery();
        $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $items = $query->getResult();

        // Считаем общее количество записей (один дополнительный запрос)
        $totalItems = $this->createQueryBuilder('user')
            ->select('COUNT(user.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = ceil($totalItems / $limit);

        return [
            'items' => $items,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'totalItems' => $totalItems,
        ];
    }
}
