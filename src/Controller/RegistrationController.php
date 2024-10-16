<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ValidationError;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{

    #[Route('/api/registration', name: 'post_single', methods: ['POST', 'GET'])]
    public function index(
        LoggerInterface $logger,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response
    {
        $data = $request->request->all();

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

            $status = 'success';
        }
        else{
            $status = 'error';
        }

        $data = [
            'status' => $status,
            'errors' => $messagesErrors
        ];

        return new JsonResponse($data, $status ==='success' ? 200 : 422 );
    }



}
