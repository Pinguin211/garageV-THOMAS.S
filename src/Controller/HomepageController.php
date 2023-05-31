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

    #[Route('/test', name: 'app_test')]
    public function token(CsrfTokenManagerInterface $csrfTokenManager, Request $request,
                          SessionInterface $session, Security $security): Response
    {
        $token = $_POST['token'];
        $u = $session->getId();
        $uu = $security->getUser();
        $t = $request->get('_token');


        $b = $csrfTokenManager->isTokenValid(new CsrfToken('hello', $token)) ? 'True' : 'False';
        return new Response($b, 200);
    }
}
