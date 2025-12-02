<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\LoginUserRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{   
    protected $userService;

    public function __construct(UserService $userService) {

        $this->userService = $userService;
    }

    public function register(CreateUserRequest $request)
    {
        try {
            $data = $request->validated();

            $this->userService->create($data);

            return $this->respondWithOk('Usuário cadastrado com sucesso');

        } catch (ValidationException $e) {

            $firstError = $e->validator->errors()->first();
            return $this->respondWithErrors($firstError);

        } catch (\Exception $e) {
            return $this->respondWithErrors($e->getMessage());
        }

    }

    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('app-token')->plainTextToken;

        $user = $this->userService->formatResponseUserDTO($user);

        return $this->respondWithOk([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user
        ]);
    }

    public function user(Request $request)
    {
        $user = $this->userService->formatResponseUserDTO($request->user());
        return $this->respondWithOk($user);
    }
}
