<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Comment;
use App\Service\AutomaticInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(AutomaticInterface $auto, EntityManagerInterface $entityManager): Response
    {

        $cars = $entityManager->getRepository(Car::class)->findAll();
        shuffle($cars);
        $cars = array_slice($cars, 0, 4);

        $rates = $entityManager->getRepository(Comment::class)->findBy(['validated' => true], ['id' => 'DESC'], 20);


        $auto->setCsrfToken();

        return $this->render('homepage/index.html.twig', [
            'cars' => $cars,
            'rates' => $rates,
            'auto' => $auto->getParams(),
        ]);
    }
}
