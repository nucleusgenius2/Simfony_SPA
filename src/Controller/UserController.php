<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;

use Psr\Log\LoggerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
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
    #[Route('/api/registration', methods: ['GET', 'HEAD'])]
    public function registration(LoggerInterface $logger, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
/*
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:16|regex:/(^[A-Za-z0-9-_]+$)+/',
            'email' => 'required|email|unique:users|max:30',
            'password' => 'required|string|confirmed|min:6|max:30',
        ]);

        if ($validated->fails()) {
            $this->text = $validated ->errors();
        } else {
            $data = $validated->valid();
*/
       //$logger->info(json_encode($request->query->all()));
        $logger->info('2222');
/*
            $user = User::create($data);

            $token = $user->createToken('token', ['permission:user'])->plainTextToken;

            $dataUser = [
                'token' => $token,
                'user' => $user->email,
            ];

            $this->status = 'success';
            $this->code = 200;
            $this->json = $dataUser;
            $this->text = 'Регистрация прошла успешно';

            //event(new Registered($user));
       // }
*/

        return new JsonResponse();
    }
/*
    public function login(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validated->fails()) {
            $this->text = $validated ->errors();
        } else {
            $user = User::where('email', $request->email)->first();

            if($user) {
                if (Hash::check($request->password, $user->password)) {

                    if ($this->isAdminPermission($user)) {
                        $token = $user->createToken('token', ['permission:admin'])->plainTextToken;
                    } else {
                        $token = $user->createToken('token', ['permission:user'])->plainTextToken;
                    }

                    $dataUser = [
                        'token' => $token,
                        'user' => $user->email,
                    ];

                    $this->status = 'success';
                    $this->code = 200;
                    $this->json = $dataUser;
                    $this->text = 'Вход успешен';

                    //UserLogin::dispatch($user);
                } else {
                    $this->text = 'Пароль не совпадает';
                }
            } else {
                $this->text = 'Email не найден';
            }
        }

        return $this->responseJsonApi();
    }


    public function index(Request $request): JsonResponse
    {
        $validated = Validator::make(['page' => $request->page], [
            'page' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $postUser = User::orderBy('id', 'desc')->paginate(10, ['*'], 'page', $data['page']);

            if (count($postUser) > 0) {
                $this->status = 'success';
                $this->code = 200;
                $this->json = $postUser;
            } else {
                $this->text = 'таблица юзеров пуста';
            }
        }

        return $this->responseJsonApi();
    }


    public function show(int $id): JsonResponse
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $contentUserSingle = User::where('id', '=', $data['id'])->get();

            if (count($contentUserSingle) > 0) {
                $this->status = 'success';
                $this->code = 200;
                $this->json = $contentUserSingle;
            } else {
                $this->text = 'юзера не существует';
            }
        }

        return $this->responseJsonApi();
    }
*/

}
