<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ValidationError;
use App\Traits\ResponseController;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    use ResponseController;

    #[Route('/api/registration', name: 'post_single', methods: ['POST', 'GET'])]
    public function index(
        LoggerInterface $logger,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
    ): Response
    {
        $data = $request->request->all();

        if (!array_key_exists('name', $data) || !array_key_exists('email', $data)|| !array_key_exists('password', $data) ) {
            $this->messagesErrors ='Не все обязательные поля заполнены';
        }
        else {
            $user = new User();

            $user->setName($data['name']);
            $user->setEmail($data['email']);

            $today = new DateTimeImmutable('now');
            $user->setCreatedAt($today);

            //создание хеша пароля юзера
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $data['password']
            );
            $user->setPassword($hashedPassword);

            //валидация
            $errors = $validator->validate($user);
            $messagesErrors = ValidationError::getMessageError($errors);

            //валидация пройдена
            if (!$messagesErrors) {
                $entityManager->persist($user);
                $entityManager->flush();

                $this->status = 'success';
                $this->code = 200;
            }
        }

        $data = [
            'status' => $this->status,
            'errors' => $this->messagesErrors
        ];

        return new JsonResponse($data, $this->code);
    }

}
