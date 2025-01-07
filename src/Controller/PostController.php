<?php

namespace App\Controller;


use App\Entity\Post;
use App\Form\PostForm;
use App\Repository\PostRepository;
use App\Service\PutParser;
use App\Traits\ResponseController;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Attribute\Route;


class PostController extends AbstractController
{
    use ResponseController;

    #[Route('/api/posts', name: 'api_post_list', methods: ['GET'])]
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


    #[Route('/api/posts/{slug}', name: 'api_post_single', methods: ['GET'])]
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


    #[Route('/api/posts', name: 'api_post_update', methods: ['PUT'])]
    public function update(
        PostRepository $repository,
        Request $request,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        PutParser $putParser
    ): Response
    {
        $parsedData = $putParser->getData($request);

        $post = $entityManager->getRepository(Post::class)->find($parsedData['id']);

        if ($post) {
            $post->setName($parsedData['name']);
            $post->setContent($parsedData['content']);
            $post->setShortDescription($parsedData['short_description']);
            if ( isset($parsedData['seo_title'])) {$post->setSeoTitle($parsedData['seo_title']);}
            if ( isset($parsedData['seo_description'])) { $post->setSeoDescription($parsedData['seo_description']); }
            if ( isset($parsedData['category_id'])) {$post->setCategoryId($parsedData['category_id']); }

            $entityManager->persist($post);
            $entityManager->flush();

            $data =  [
                'id' => $post->getId(),
                'name' => $post->getName(),
                'content' => $post->getContent(),
                'short_description' => $post->getShortDescription(),
                'seo_title' => $post->getSeoTitle(),
                'seo_description' => $post->getSeoDescription(),
                'category_id' => $post->getCategoryId(),
            ];

            $this->status = 'success';
            $this->code = 200;
        }
        else{
            $this->status = 'error';
            $errorMessages = 'запись не найдена';
        }

        $data = [
            'status' => $this->status,
            'json' => [
                'data' => $data ?? [],
            ],
            'text' => $errorMessages ?? ''
        ];

        return new JsonResponse($data, $this->code);
    }
}
