<?php

namespace App\Controller;


use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class PostController extends AbstractController
{
    #[Route('/api/post/{page}', name: 'post_list', methods: ['GET'], requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(
        LoggerInterface $logger,
        PostRepository $repository,
        int $page,
    ): Response
    {
        $paginator = $repository->findPaginated($page, 10);


        $postData = [];
        $status = 'success';
        if ( count($paginator) > 0) {
            foreach ($paginator as $post) {
                $postData[$post->getid()] =[
                    $post->getAll()
                ];
            }
        }
        else{
            $status ='error';
        }
        foreach ($paginator as $post) {

            $logger->info($post->getName() );
        }


        $data = [
            'status' => $status,
            'json' => $postData
        ];

        return new JsonResponse($data);
    }

    #[Route('/api/post/{page}', name: 'post_single', methods: ['GET'])]
    public function show(int $page, LoggerInterface $logger, PostRepository $productRepository, Request $request): Response
    {
        $post = $productRepository->find($page);

        $data = [
            'status' => 'success',
            'json' => $post->getName()
        ];
        return new JsonResponse($data);
    }
}
