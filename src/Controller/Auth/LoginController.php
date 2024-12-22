<?php
namespace App\Controller\Auth;

use App\Repository\UserRepository;
use App\Service\TokenGenerator;
use App\Traits\ResponseController;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController {
    use ResponseController;

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        LoggerInterface $logger,
        UserRepository $repository,
        TokenGenerator $tokenGenerator,
    ): Response
    {
        $data = $request->request->all();

        if (!array_key_exists('email', $data) || !array_key_exists('password', $data) ) {
            $this->messagesErrors ='Не все обязательные поля заполнены';
        }
        else {
            $user = $repository->findByUserEmail($data['email']);

            //проверка пароля юзера
            if ($user) {
                if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
                    $this->messagesErrors = 'Не верный пароль';
                } else {
                    $this->status = 'success';
                    $this->code = 200;
                    $token = $tokenGenerator->createToken($user);
                }
            } else {
                $this->messagesErrors = 'Пользователь не найден';
            }
        }

        $dataUser = [
            'token' => $token ?? 'error',
            'user' => $user->getEmail(),
        ];

        return $this->json([
            'status' => $this->status,
            'json' =>  $dataUser ,
            'errors' => $this->messagesErrors
        ],  $this->code );
    }
}
