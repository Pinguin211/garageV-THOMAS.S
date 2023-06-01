<?php

namespace App\Controller;

use App\Service\AutomaticInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(AutomaticInterface $auto): Response
    {

        return $this->render('homepage/index.html.twig', [
            'auto' => $auto->getParams(),
        ]);
    }
}
