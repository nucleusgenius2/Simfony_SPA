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
        $query = $this->createQueryBuilder('post')
            ->orderBy('post.id', 'ASC')
            ->getQuery()
            ->setFirstResult(($currentPage - 1) * $limit)
            ->setMaxResults($limit);


        $paginator = new Paginator($query, true);

        $totalItems = count($paginator);

        // Расчёт общего количества страниц
        $totalPages = ceil($totalItems / $limit);

        return [
            'items' => iterator_to_array($paginator), // Данные текущей страницы
            'totalPages' => $totalPages,             // Общее количество страниц
            'currentPage' => $currentPage,           // Текущая страница
            'totalItems' => $totalItems,             // Общее количество записей
        ];

        //return new Paginator($query, true);
    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
