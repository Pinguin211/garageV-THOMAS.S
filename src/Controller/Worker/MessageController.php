<?php

namespace App\Controller\Worker;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\AutomaticInterface;
use App\Service\CheckerInterface;
use App\Service\RolesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(AutomaticInterface $automatic, Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        if (isset($_GET['message']))
            $message->setMessage($_GET['message']);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setDate(new \DateTime());
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('app_message', [
                'title' => 'Message envoyé',
                'message' => "Le personnel du garage vous recontactera dans les plus bref délais",
            ]);
        }

        return $this->render('contact/contact.html.twig', [
            'auto' => $automatic->getParams(),
            'form' => $form->createView()
        ]);
    }

    #[Route('/worker/message_management', name: 'app_message_management')]
    public function management(AutomaticInterface $automatic): Response
    {
        if (!$this->isGranted(RolesInterface::ROLE_WORKER))
            return $this->redirectToRoute('app_homepage');

        $automatic->setCsrfToken();

        return $this->render('worker/message_management.html.twig', [
            'auto' => $automatic->getParams(),
        ]);
    }

    #[Route('/worker/message_information')]
    public function info(CheckerInterface $checker, EntityManagerInterface $entityManager): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_WORKER))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);

        $messages = $entityManager->getRepository(Message::class)->findBy([], ['id' => 'DESC']);
        $res = [];
        foreach ($messages as $message)
        {
            $res[] = [
                'id' => $message->getId(),
                'name' => $message->getName(),
                'phone' => $message->getPhone(),
                'email' => $message->getEmail(),
                'message' => $message->getMessage(),
                'date' => $message->getDate()->format('d-m-Y')
            ];
        }
        return new Response(json_encode($res));
    }

    #[Route('/worker/message_delete')]
    public function delete(CheckerInterface $checker, EntityManagerInterface $entityManager): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_WORKER))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);
        if (!$checker->checkArrayData($_POST, 'ids', 'array'))
            return new Response('Bad request', 403);

        $messages = $entityManager->getRepository(Message::class)->findBy(['id' => $_POST['ids']]);
        foreach ($messages as $message)
            $entityManager->remove($message);
        $entityManager->flush();
        return new Response();
    }
}
