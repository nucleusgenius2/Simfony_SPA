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

    #[Route('/api/posts', name: 'post_list', methods: ['GET'])]
    public function index(
        PostRepository $repository,
        Request $request,
        LoggerInterface $logger
    ): Response
    {
        $page = $request->query->getInt('page', 1);
        $paginator = $repository->findPaginated($page, 10);

        if ( count($paginator['items']) > 0 ) {
            $this->status = 'success';
            $this->code = 200;
        }

        $data = [
            'status' => $this->status,
            'json' => [
                'data' => $paginator['items'],
                'last_page' => $paginator['totalPages'],
                'current_page' => $paginator['currentPage'],
            ]
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
