<?php

namespace App\Entity;

use App\Service\PathInterface;
use Doctrine\ORM\EntityManagerInterface;

class Worker extends EntityController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function addCar(Car $car): void
    {
        $this->pushObject($car);
    }

    public function addOptions(Option $option): void
    {
        $this->pushObject($option);
    }

    public function addOptionToRemoveList(Option $option)
    {
        $this->addObjectToRemoveList($option);
    }

    public function addCarToRemoveList(Car $car, PathInterface $path)
    {
        $names = $car->getImagesNames();
        foreach ($names as $name)
            unlink($path->getCarImagesDirPath() . $name);
        $this->addObjectToRemoveList($car);
    }
}