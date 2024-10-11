<?php

namespace App\Controller;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/registration', name: 'post_single', methods: ['POST', 'GET'])]
    public function index(LoggerInterface $logger, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
       // $params = $request->query->get('bar');
        $logger->info(json_encode($request->request));


        /*
        // ... e.g. get the user data from a registration form
        $user = new User(...);
        $plaintextPassword = ...;

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        // ...
        */
        $data = [
           // 'success' => $request->getContent(),
            'post' => file_get_contents('php://input'),
            'get' => $request->query
        ];

        return new JsonResponse($data);
    }
}
