<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $user = $this->userService->show($id);

        if (!$user) {
            return $this->respondWithErrors(['message' => 'User not found'], 404);
        }

        return $this->respondWithOk($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($id, 'id'),
                ],
                'cellphone' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users', 'cellphone')->ignore($id, 'id'),
                ],
                'document' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users', 'document')->ignore($id, 'id'),
                ],
                'password' => 'sometimes|nullable|string|min:6|confirmed',
                'driver_document' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('users', 'driver_document')->ignore($id, 'id'),
                ],
                'driver_document_code' => [
                    'sometimes',
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('users', 'driver_document_code')->ignore($id, 'id'),
                ],
                'profile_photo' => 'sometimes|nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();

            $user = $this->userService->update($id, $data);

            if (!$user) {
                return $this->respondWithErrors(['message' => 'User not found or update failed'], 404);
            }

            return $this->respondWithOk($user);

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
