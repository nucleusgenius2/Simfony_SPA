<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\TokenGenerator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController {
    /*
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(
        AuthenticationUtils $authenticationUtils,
        #[CurrentUser] ?User $user
    ): Response
    {
        if (null === $user) {
            return $this->json([
                 'message' => 'кароче мнебы понятто',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = '123'; // somehow create an API token for $user

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiLoginController.php',
            'user'  => $user->getUserIdentifier(),
            'token' => $token,
        ]);
   }
    */

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
        $logger->info('---------');
        $logger->info($data['password']);
        $logger->info($data['email']);

        $user = $repository->findByUserEmail($data['email']);

        if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
            $logger->info('ошибка авторизации');
            $token = 'error';
        }
        else{
            $logger->info('успех авторизации');
            $token = $tokenGenerator->createToken($user);
        }


        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiLoginController.php',
            'user'  => $user,
            'token' => $token,
        ]);
    }


}
