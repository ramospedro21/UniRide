<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userService->formatResponseUserDTO($this->userService->show($id));

        if (!$user) {
            return $this->respondWithErrors(['message' => 'User not found'], 404);
        }

        return $this->respondWithOk($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {

            $data = $request->validated();

            $user = $this->userService->update($id, $data);

            if (!$user) {
                return $this->respondWithErrors(['message' => 'User not found or update failed'], 404);
            }

            $userToReturn = $this->userService->formatResponseUserDTO($this->userService->show($id));

            return $this->respondWithOk($userToReturn);

        } catch (ValidationException $e) {
            $firstError = $e->validator->errors()->first();
            return $this->respondWithErrors($firstError);
        } catch (\Exception $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
