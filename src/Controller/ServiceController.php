<?php

namespace App\Controller;

use App\Entity\Service;
use App\Service\AutomaticInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    public function index(AutomaticInterface $automatic, EntityManagerInterface $entityManager): Response
    {
        $services = $entityManager->getRepository(Service::class)->findBy([], ['name' => 'ASC']);

        return $this->render('services/index.html.twig', [
            'auto' => $automatic->getParams(),
            'services' => $services
        ]);
    }

}
