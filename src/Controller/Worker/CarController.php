<?php

namespace App\Controller\Worker;

use App\Entity\Car;
use App\Entity\Worker;
use App\Form\CarType;
use App\Service\AutomaticInterface;
use App\Service\CheckerInterface;
use App\Service\PathInterface;
use App\Service\RolesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{

    #[Route('/worker/car', name: 'app_worker_car')]
    public function index(AutomaticInterface $auto, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isGranted(RolesInterface::ROLE_WORKER))
            return $this->redirectToRoute('app_homepage');
        else
            $worker = new Worker($entityManager);

        if (isset($_GET['id']) &&
            ($car = $entityManager->getRepository(Car::class)->findOneBy(['id' => $_GET['id']])))
            $mod = true;
        else {
            $car = new Car();
            $mod = false;
        }

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($mod) {
                $worker->flush();
                return $this->redirectToRoute('app_message', [
                    'title' => 'Voiture modifié',
                    'message' => "Les modifications sont désormais visible par les visiteurs",
                    'redirect_app' => 'app_homepage'
                ]);
            } else {
                $worker->addCar($car);
                return $this->redirectToRoute('app_message', [
                    'title' => 'Voiture ajouté',
                    'message' => "La voiture est désormais visible par les autres visiteurs",
                    'redirect_app' => 'app_homepage'
                ]);
            }
        }

        $auto->setCsrfToken();

        return $this->render('worker/car.html.twig', [
            'auto' => $auto->getParams(),
            'form' => $form->createView(),
            'car' => $car,
            'mod' => $mod,
        ]);
    }

    #[Route('/worker/get_car_image_path')]
    public function addCarImage(CheckerInterface $checker, EntityManagerInterface $entityManager): Response
    {
        if (!$checker->checkArrayData($_POST, 'id', 'numeric') ||
            !($car = $entityManager->getRepository(Car::class)->findOneBy(['id' => $_POST['id']])))
            return new Response(json_encode([]));
        else
            return new Response(json_encode($car->getImagesNames()));
    }

}
