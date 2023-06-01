<?php

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface;

abstract class EntityController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }


    public function flush(): void
    {
        $this->entityManager->flush();
    }

    protected function pushObject(mixed $obj): void
    {
        $this->entityManager->persist($obj);
        $this->entityManager->flush();
    }

    protected function removeObject(mixed $obj): void
    {
        $this->entityManager->remove($obj);
        $this->entityManager->flush();
    }

    protected function addObjectToRemoveList(mixed $obj): void
    {
        $this->entityManager->remove($obj);
    }
}