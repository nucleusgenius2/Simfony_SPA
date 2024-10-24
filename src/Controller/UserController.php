<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Traits\ResponseController;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    use ResponseController;

    #[Route('/api/user', name: 'user_list', methods: ['GET'])]
    public function index(
        UserRepository $repository,
        Request $request,
    ): Response
    {

        $page = $request->query->get('page');

        if ( $page && intval($page > 0) ){
            $paginator = $repository->findPaginated(intval($page), 20);

            $userData = [];

            if (count($paginator) > 0) {
                foreach ($paginator as $user) {
                    $userData[$user->getid()] = [
                        $user->getAll()
                    ];
                }

                $status = 'success';
            } else {
                $status = 'error';
            }
        }
        else{
            $status = 'error';
        }

        $data = [
            'status' => $status,
            'json' => $userData
        ];

        return new JsonResponse($data, $status === 'success' ? 200 : 422);
    }


    #[Route('/api/user/{id}', name: 'user_single', methods: ['GET'])]
    public function show(int $id, PostRepository $productRepository): Response
    {
        $user = $productRepository->find($id);

        $status = $user ? 'success' : 'error';

        $data = [
            'status' => $status ,
            'json' => $user->getAll()
        ];

        return new JsonResponse($data, $status ==='success' ? 200 : 422 );
    }

    #[Route('/api/authorization', name: 'check_auth', methods: ['GET'])]
    public function checkStatusUser()
    {
       $user = $this->getUser();

        $data = [
            'status' => 'success',
            'json' => [
                'role' =>$user->getRoles()
            ]

        ];
        return new JsonResponse($data, 202);
    }

    /*
public function isAdminPermission(User $user): bool
{
    if ($user->status === 2) {
        return true;
    } else {
        return false;
    }
}




public function logout(): JsonResponse
{
    $user = request()->user();

    $user->tokens()->delete();

    $data = ['status' => 'success'];

    return response()->json($data, 200);
}
*/


}
