<?php

namespace App\Services\User;

use App\Repositories\User\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function all()
    {
        return $this->userRepository->all();
    }

    public function create(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function show($id)
    {
        return $this->userRepository->find($id);
    }

    public function update($id, array $data)
    {
        $user = $this->userRepository->find($id);
        return $this->userRepository->update($user, $data);
    }

    public function delete($id)
    {
        $user = $this->userRepository->find($id);
        return $this->userRepository->delete($user);
    }

}