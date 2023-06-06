<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Entity\Worker;
use App\Form\ServiceType;
use App\Service\AutomaticInterface;
use App\Service\CheckerInterface;
use App\Service\PathInterface;
use App\Service\RolesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{


    #[Route('/admin/service', name: 'app_admin_service')]
    public function index(AutomaticInterface $auto, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->isGranted(RolesInterface::ROLE_ADMIN))
            return $this->redirectToRoute('app_homepage');
        else
            $worker = new Worker($entityManager);

        if (isset($_GET['id']) &&
            ($service = $entityManager->getRepository(Service::class)->findOneBy(['id' => $_GET['id']])))
            $mod = true;
        else {
            $service = new Service();
            $mod = false;
        }

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($mod) {
                $worker->flush();
                return $this->redirectToRoute('app_message', [
                    'title' => 'Service modifié',
                    'message' => "Les modifications sont désormais visible par les visiteurs",
                ]);
            } else {
                $worker->addService($service);
                return $this->redirectToRoute('app_message', [
                    'title' => 'Voiture ajouté',
                    'message' => "Le services est désormais visible par les autres visiteurs",
                ]);
            }
        }

        $auto->setCsrfToken();

        return $this->render('admin/service.html.twig', [
            'auto' => $auto->getParams(),
            'form' => $form->createView(),
            'car' => $service,
            'mod' => $mod,
        ]);
    }

    #[Route('/admin/get_service_image_path')]
    public function serviceImagePath(CheckerInterface $checker, EntityManagerInterface $entityManager): Response
    {
        if (!$checker->checkArrayData($_POST, 'id', 'numeric') ||
            !($service = $entityManager->getRepository(Service::class)->findOneBy(['id' => $_POST['id']])))
            return new Response('');
        else
            return new Response($service->getImageName());
    }


    #[Route('/admin/service_management', name: 'app_admin_service_management')]
    public function manage_service(AutomaticInterface $auto): Response
    {
        if (!$this->isGranted(RolesInterface::ROLE_ADMIN))
            return $this->redirectToRoute('app_homepage');

        $auto->setCsrfToken();

        return $this->render('admin/service_management.html.twig', [
            'auto' => $auto->getParams(),
        ]);
    }


    #[Route('/admin/service_delete')]
    public function deleteService(CheckerInterface $checker, EntityManagerInterface $entityManager, PathInterface $path): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_ADMIN))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);
        if (!$checker->checkArrayData($_POST, 'ids', 'array'))
            return new Response('Bad request', 403);

        $service_ids = $_POST['ids'];
        $services = $entityManager->getRepository(Service::class)->findBy(['id' => $service_ids]);
        $worker = new Worker($entityManager);
        foreach ($services as $service) {
            $worker->addServiceToRemoveList($service, $path);
        }
        $worker->flush();
        return new Response();
    }



    /* INFORMATION SUR LES VOITURES EN ANNONCES */

    #[Route('/service_informations')]
    public function getServicesInfo(EntityManagerInterface $entityManager): Response
    {
        $services = $entityManager->getRepository(Service::class)->findBy([], ['name' => 'ASC']);
        $res = [];
        foreach ($services as $service) {
            $res[] = [
                'name' => $service->getName(),
                'id' => $service->getId()
            ];
        }
        return new Response(json_encode($res));
    }
}
