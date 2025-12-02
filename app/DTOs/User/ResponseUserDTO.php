<?php

namespace App\DTOs\User;

use App\Models\User;

readonly class ResponseUserDTO
{
    public string $id;
    public string $name;
    public string $email;
    public ?string $phone;
    public string $document;
    public ?string $driverDocument;
    public ?string $profilePhoto;
    public string $created_at;
    public string $updated_at;

    public function __construct(User $user)
    {
        $this->id             = $user->id;
        $this->name           = $user->name;
        $this->email          = $user->email;
        $this->phone          = $user->phone;
        $this->document       = $user->document;
        $this->driverDocument = $user->driverDocument;
        $this->profilePhoto   = $user->profilePhoto;
        $this->created_at     = $user->created_at;
        $this->updated_at     = $user->updated_at;
    }
}