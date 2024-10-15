<?php

namespace App\Controller;


use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        Request $request,
    ): Response
    {
        $paginator = $repository->findPaginated($page, 10);

        $postData = [];

        if ( count($paginator) > 0 ) {
            foreach ($paginator as $post) {
                $postData[$post->getid()] =[
                    $post->getAll()
                ];
            }

            $status = 'success';
        }
        else{
            $status ='error';
        }

        $data = [
            'status' => $status,
            'json' => $postData
        ];

        return new JsonResponse($data, $status ==='success' ? 200 : 422 );
    }

    #[Route('/api/post/{slug}', name: 'post_single', methods: ['GET'])]
    public function show(int $slug, LoggerInterface $logger, PostRepository $productRepository, Request $request): Response
    {
        $post = $productRepository->find($slug);

        $status = $post ? 'success' : 'error';

        $data = [
            'status' => $status ,
            'json' => $post->getAll()
        ];

        return new JsonResponse($data, $status ==='success' ? 200 : 422 );
    }
}
