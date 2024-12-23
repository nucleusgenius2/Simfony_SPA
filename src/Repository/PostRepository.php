<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findPaginated(int $currentPage = 1, int $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('post')
            ->select('post.id, post.name, post.content, post.created_at, post.short_description, post.img')
            ->orderBy('post.id', 'ASC')
            ->setFirstResult(($currentPage - 1) * $limit)
            ->setMaxResults($limit);


        $query = $queryBuilder->getQuery();
        $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $items = $query->getResult();

        // Считаем общее количество записей (один дополнительный запрос)
        $totalItems = $this->createQueryBuilder('post')
            ->select('COUNT(post.id)')
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
