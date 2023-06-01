<?php

namespace App\Entity;

use App\Service\RolesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Admin extends EntityController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function createWorker(string $email, string $plain_password, UserPasswordHasherInterface $hasher)
    {
        $worker = new User();
        $worker->setRoles([RolesInterface::ROLE_WORKER]);
        $worker->setEmail($email);
        $worker->setPassword($hasher->hashPassword($worker, $plain_password));
        $this->pushObject($worker);
    }

    public function addWorkerToRemoveList(User $user, RolesInterface $roles): void
    {
        if ($roles->is_worker($user))
            $this->addObjectToRemoveList($user);
    }
}