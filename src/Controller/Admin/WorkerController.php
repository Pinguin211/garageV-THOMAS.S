<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\User;
use App\Service\AutomaticInterface;
use App\Service\CheckerInterface;
use App\Service\RolesInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class WorkerController extends AbstractController
{
    private const MAX_EMAIL_LEN = 100;
    private const EMAIL_REGEX = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    private const MAX_PASSWORD_LEN = 100;
    private const PASSWORD_REGEX = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';

    #[Route('/admin/worker', name: 'app_worker')]
    public function index(AutomaticInterface $auto): Response
    {
        if (!$this->isGranted(RolesInterface::ROLE_ADMIN))
            return $this->redirectToRoute('app_homepage');

        $auto->setCsrfToken();

        return $this->render('admin/worker.html.twig', [
            'auto' => $auto->getParams(),
            'max_email_len' => self::MAX_EMAIL_LEN,
            'max_password_len' => self::MAX_PASSWORD_LEN,
        ]);
    }

    #[Route('/admin/worker_list')]
    public function sendOptionList(CheckerInterface $checker, EntityManagerInterface $entityManager, RolesInterface $roles): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_ADMIN))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);

        $list = [];
        $workers = $entityManager->getRepository(User::class)->findBy([], ['email' => 'ASC']);
        foreach ($workers as $worker)
            if ($roles->is_worker($worker))
                $list[] = ['id' => $worker->getId(), 'email' => $worker->getEmail()];
        return new Response(json_encode($list));

    }

    #[Route('/admin/add_worker')]
    public function addAddWorker(CheckerInterface $checker, EntityManagerInterface $entityManager,
                                  UserPasswordHasherInterface $hasher): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_ADMIN))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);
        if (!$checker->checkArrayData($_POST, 'email', 'string') ||
            !$checker->checkArrayData($_POST, 'password', 'string'))
            return new Response('Bad request', 403);

        $email = $_POST['email'];
        if (!$checker->checkUserInput($email, self::MAX_EMAIL_LEN) ||
            !preg_match(self::EMAIL_REGEX, $email) ||
            $entityManager->getRepository(User::class)->findOneBy(['email' => $email]))
            return new Response('Bad email', 403);

        $password = $_POST['password'];
        if (strlen($password) > self::MAX_PASSWORD_LEN ||
            !preg_match(self::PASSWORD_REGEX, $password))
            return new Response('Bad password', 403);

        $admin = new Admin($entityManager);
        $admin->createWorker($email, $password, $hasher);
        return new Response();
    }

    #[Route('/admin/delete_worker')]
    public function deleteWorker(CheckerInterface $checker, EntityManagerInterface $entityManager, RolesInterface $roles): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_ADMIN))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);
        if (!$checker->checkArrayData($_POST, 'ids', 'array'))
            return new Response('Bad request', 403);

        $ids = $_POST['ids'];
        $users = $entityManager->getRepository(User::class)->findBy(['id' => $ids]);
        $admin = new Admin($entityManager);
        foreach ($users as $user)
            $admin->addWorkerToRemoveList($user, $roles);
        $admin->flush();
        return new Response();
    }
}
