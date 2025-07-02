<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserService;
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
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'cellphone' => 'required|string|max:11',
                'document' => 'required|string|max:11'
            ]);
    
            $this->userService->create($data);
    
            return $this->respondWithOk('Usuário cadastrado com sucesso');
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
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
