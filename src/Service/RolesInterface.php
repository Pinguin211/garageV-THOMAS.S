<?php

namespace App\Service;

use App\Entity\User;

class RolesInterface
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_WORKER = 'ROLE_WORKER';


    public function is_worker(User $user): bool
    {
        return in_array(self::ROLE_WORKER, $user->getRoles());
    }

    public function is_admin(User $user): bool
    {
        return in_array(self::ROLE_ADMIN, $user->getRoles());
    }
}