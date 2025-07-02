<?php

namespace App\Repositories\User;

Use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository 
{
    public function all()
    {
        return User::get();
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        if(isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $user->update($data);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}