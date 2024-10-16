<?php
namespace App\Controller\Auth;

use App\Repository\UserRepository;
use App\Service\TokenGenerator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController {

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        LoggerInterface $logger,
        UserRepository $repository,
        TokenGenerator $tokenGenerator
    ): Response
    {
        $data = $request->request->all();

        $user = $repository->findByUserEmail($data['email']);

        //проверка пароля юзера
        if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
            $status = 'error';
        }
        else{
            $status = 'success';
            $token = $tokenGenerator->createToken($user);
        }


        return $this->json([
            'status' => $status,
            'json' => $user,
            'token' => $token ?? 'error',
        ], $status ==='success' ? 200 : 422 );
    }


}
