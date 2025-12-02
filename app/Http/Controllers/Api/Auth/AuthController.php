<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{   
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'cellphone' => 'required|string|max:11',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'document' => 'required|string|unique:users|max:11',
                'driver_document' => 'sometimes|nullable|string|unique:users|max:11'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();

            $this->userService->create($data);

            return $this->respondWithOk('Usuário cadastrado com sucesso');

        } catch (ValidationException $e) {

            $firstError = $e->validator->errors()->first();
            return $this->respondWithErrors($firstError);

        } catch (\Exception $e) {
            return $this->respondWithErrors($e->getMessage());
        }

    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('app-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
