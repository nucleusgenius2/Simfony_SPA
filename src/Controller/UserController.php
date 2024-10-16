<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
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

    /*
public function isAdminPermission(User $user): bool
{
    if ($user->status === 2) {
        return true;
    } else {
        return false;
    }
}


public function checkStatusUser(Request $request): JsonResponse
{
    $user = request()->user();

    if ($user->tokenCan('permission:admin')) {
        $data = ['status' => 'success', 'permission' => 'admin'];
    } else {
        $data = ['status' => 'success', 'permission' => 'user'];
    }

    return response()->json($data, 200);
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
