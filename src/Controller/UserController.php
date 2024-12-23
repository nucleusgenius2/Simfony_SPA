<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\ValidationError;
use App\Traits\ResponseController;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    use ResponseController;

    #[Route('/api/users', name: 'users_list', methods: ['GET'])]
    public function index(
        UserRepository $repository,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {

        $page = $request->query->get('page');

        if ( $page && intval($page > 0) ){
            $paginator = $repository->findPaginated(intval($page), 20);

            if (count($paginator['items']) > 0) {
                $this->status = 'success';
                $this->code = 200;
            }
        }

        $data = [
            'status' => $this->status,
            'json' => [
                'data' => $paginator['items'], // Уже массивы данных
                'last_page' => $paginator['totalPages'],
                'current_page' => $paginator['currentPage'],
            ]
        ];

        return new JsonResponse($data, $this->code);
    }


    #[Route('/api/users/{id}', name: 'user_single', methods: ['GET'])]
    public function show(int $id, userRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        $status = $user ? 'success' : 'error';

        $data = [
            'status' => $status ,
            'json' => $user->getFullData()
        ];

        return new JsonResponse($data, $status ==='success' ? 200 : 422 );
    }

    #[Route('/api/users', name: 'api_user_update', methods: ['PUT'])]
    public function update(
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
    ){
        $data = json_decode($request->getContent(), true);

        $user = $this->getUser();

        if (array_key_exists('email', $data) ){
            $user->setEmail($data['email']);
        }
        if (array_key_exists('name', $data) ){
            $user->setName($data['name']);
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

        $user = $this->getUser();

        $data = [
            'status' => $this->status,
            'json' => $user->getPublicData(),
            'errors' => $this->messagesErrors
        ];

        return new JsonResponse($data, $this->code);
    }

    #[Route('/api/authorization', name: 'check_auth', methods: ['GET'])]
    public function checkStatusUser()
    {
        $user = $this->getUser();

        $data = [
            'status' => 'success',
            'json' => [
                'role' =>$user->getRolesStr()
            ]

        ];
        return new JsonResponse($data, 202);
    }


}
