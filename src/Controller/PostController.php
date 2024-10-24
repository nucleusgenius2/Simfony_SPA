<?php

namespace App\Controller;


use App\Entity\Post;
use App\Repository\PostRepository;
use App\Traits\ResponseController;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class PostController extends AbstractController
{
    use ResponseController;

    #[Route('/api/posts/{page}', name: 'post_list', methods: ['GET'], requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(
        PostRepository $repository,
        int $page,
        Request $request,
    ): Response
    {
        $paginator = $repository->findPaginated($page, 10);

        $postData = [];

        if ( count($paginator) > 0 ) {
            foreach ($paginator as $post) {
                $postData[$post->getid()] = $post->getAll();
            }

            $this->status = 'success';
            $this->code = 200;
        }

        $data = [
            'status' => $this->status,
            'json' => $postData
        ];

        return new JsonResponse($data, $this->code);
    }

    #[Route('/api/posts/{slug}', name: 'post_single', methods: ['GET'])]
    public function show(int $slug, LoggerInterface $logger, PostRepository $productRepository, Request $request): Response
    {
        $post = $productRepository->find($slug);

        if($post) {
            $this->status = 'success';
            $this->code = 200;
        }

        $data = [
            'status' => $this->status ,
            'json' => $post->getAll()
        ];

        return new JsonResponse($data, $this->code);
    }
}
