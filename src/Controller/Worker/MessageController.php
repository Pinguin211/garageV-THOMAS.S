<?php

namespace App\Controller\Worker;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\AutomaticInterface;
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

}
