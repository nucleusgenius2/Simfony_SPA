<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(
        LoggerInterface $logger,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response
    {
       // $params = $request->query->get('bar');
        $data = $request->request->all();
        $logger->info('---------');
        $logger->info($data['name']);
        $logger->info($data['email']);
        $logger->info($data['password']);

        // ... e.g. get the user data from a registration form
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);

        $today = new DateTimeImmutable('now');
        $user->setCreatedAt($today);

        $plaintextPassword = $data['password'];

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

       // $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));

        $entityManager->persist($user);
        $entityManager->flush();

        //dd($request);
        $data = [
           // 'success' => $request->getContent(),
            'post' => $request->request->all(),
            'get' => $request->query->all()
        ];

        return new JsonResponse($data);
    }
}
