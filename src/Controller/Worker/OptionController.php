<?php

namespace App\Controller\Worker;

use App\Entity\Option;
use App\Entity\Worker;
use App\Service\AutomaticInterface;
use App\Service\CheckerInterface;
use App\Service\RolesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OptionController extends AbstractController
{
    private const MAX_LENGHT_OPTION_NAME = 100;

    #[Route('/worker/option', name: 'app_worker_option')]
    public function index(AutomaticInterface $auto): Response
    {
        if (!$this->isGranted(RolesInterface::ROLE_WORKER))
            return $this->redirectToRoute('app_homepage');

        $auto->setCsrfToken();

        return $this->render('worker/option.html.twig', [
            'auto' => $auto->getParams(),
            'max_len' => self::MAX_LENGHT_OPTION_NAME
        ]);
    }

    #[Route('/worker/option_list')]
    public function sendOptionList(CheckerInterface $checker, EntityManagerInterface $entityManager): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_WORKER))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);

        $list = [];
        $options = $entityManager->getRepository(Option::class)->findBy([], ['name' => 'ASC']);
        foreach ($options as $option)
            $list[] = ['id' => $option->getId(), 'name' => $option->getName()];
        return new Response(json_encode($list));

    }

    #[Route('/worker/option_add')]
    public function addOptionList(CheckerInterface $checker, EntityManagerInterface $entityManager): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_WORKER))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);
        if (!$checker->checkArrayData($_POST, 'option', 'string'))
            return new Response('Bad request', 403);
        $new_opt = $_POST['option'];
        if (!$checker->checkUserInput($new_opt, self::MAX_LENGHT_OPTION_NAME))
            return new Response('Bad request', 403);

        if ($entityManager->getRepository(Option::class)->findOneBy(['name' => $new_opt]))
            return new Response('1');
        else
        {
            $worker = new Worker($entityManager);
            $worker->addOptions(new Option($new_opt));
            return new Response('2');
        }
    }

    #[Route('/worker/option_delete')]
    public function deleteOptionList(CheckerInterface $checker, EntityManagerInterface $entityManager): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_WORKER))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);
        if (!$checker->checkArrayData($_POST, 'options', 'array'))
            return new Response('Bad request', 403);

        $options_ids = $_POST['options'];
        $options = $entityManager->getRepository(Option::class)->findBy(['id' => $options_ids]);
        $worker = new Worker($entityManager);
        foreach ($options as $option) {
            $worker->addOptionToRemoveList($option);
        }
        $worker->flush();
        return new Response();
    }
}
