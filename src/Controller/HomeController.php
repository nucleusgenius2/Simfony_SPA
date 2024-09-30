<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        $data = [
            'success' => true,
            'payload' => [
                'message' => 'Data fetched successfully',
                'data' => '22' // your data here
            ],
        ];

        return new JsonResponse($data);
    }
}
