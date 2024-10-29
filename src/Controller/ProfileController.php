<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\ValidationError;
use App\Traits\ResponseController;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfileController extends AbstractController
{
    use ResponseController;

    #[Route('/api/profile', name: 'api_profile', methods: ['GET'])]
    public function index(){

        $user = $this->getUser();

        $data = [
            'status' => 'success',
            'json' => $user->getPublicData()
        ];

        return new JsonResponse($data,  200 );
    }


    #[Route('/api/profile', name: 'api_profile_update', methods: ['PUT', 'PATCH', 'POST'])]
    public function update(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
    ){
       // $data = $request->request->all();
        $data = json_decode($request->getContent(), true);
        $logger->info('дата '.json_encode($data));

        $user = $this->getUser();

        if (array_key_exists('email', $data) ){
             $user->setEmail($data['email']);
        }
        if (array_key_exists('name', $data) ){
            $user->setName($data['name']);
            $logger->info('мя сменили '.$data['name']);
        }
        if (array_key_exists('password', $data) && array_key_exists('newPassword', $data)){
            if ($data['password'] === $data['newPassword']) {
                //создание хеша пароля юзера
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $data['password']
                );
                $user->setPassword($hashedPassword);
            }
            else{
                $this->messagesErrors ='пароли не совпадают';
            }
        }

        //валидация
        $errors = $validator->validate($user);
        $this->messagesErrors = ValidationError::getMessageError($errors);

        //валидация пройдена
        if (!$this->messagesErrors) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->status = 'success';
            $this->code = 200;
        }

       // $this->messagesErrors ='Не все обязательные поля заполнены';

        $user = $this->getUser();

        $data = [
            'status' => $this->status,
            'json' => $user->getPublicData(),
            'errors' => $this->messagesErrors
        ];

        return new JsonResponse($data, $this->code);
    }
}
