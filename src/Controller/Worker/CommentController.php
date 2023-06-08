<?php

namespace App\Controller\Worker;

use App\Entity\Comment;
use App\Service\AutomaticInterface;
use App\Service\CheckerInterface;
use App\Service\RolesInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/worker/comment_management', name: 'app_comment_management')]
    public function commentManagement(AutomaticInterface $auto): Response
    {
        if (!$this->isGranted(RolesInterface::ROLE_WORKER))
            return $this->redirectToRoute('app_homepage');

        $auto->setCsrfToken();

        return $this->render('worker/comment_management.html.twig', [
            'auto' => $auto->getParams(),
        ]);
    }


    #[Route('/add_comment')]
    public function addComment(EntityManagerInterface $entityManager, CheckerInterface $checker)
    {
        if (!$checker->checkCsrfToken() ||
            !$checker->checkData($_POST, 'array', ['poster', 'comment', 'note']) ||
            !$checker->checkData($_POST['note'], 'numeric') ||
            !$checker->checkData($_POST['poster'], 'string') ||
            !$checker->checkUserInput($_POST['poster'], 100) ||
            !$checker->checkData($_POST['comment'], 'string') ||
            !$checker->checkUserInput($_POST['comment'], 100)
        )
            return new Response('Bad request', 401);

        $comment = $_POST['comment'];
        $poster = $_POST['poster'];
        $date = new DateTime();
        $validate = (bool)$checker->checkUser(RolesInterface::ROLE_WORKER);

        $nb = (int)$_POST['note'];
        if ($nb > 5)
            $note = 5;
        elseif ($nb < 1)
            $note = 1;
        else
            $note = $nb;

        $avis = new Comment($note, $poster, $comment, $validate, $date);
        $entityManager->persist($avis);
        $entityManager->flush();
        return $validate ?
            new Response('Votre avis est maintenant visible') :
            new Response('Votre avis est en attente de validation');
    }

    #[Route('/comment_informations')]
    public function getCommentInfo(EntityManagerInterface $entityManager, CheckerInterface $checker): Response
    {
        if (!$checker->checkArrayData($_POST, 'option', 'numeric') ||
            !in_array((int)$_POST['option'], [1, 2, 3])
        )
            return new Response('Bad request', 401);

        $opt = (int)$_POST['option'];

        if ($opt > 1 &&
            $checker->checkUser(RolesInterface::ROLE_WORKER) &&
            $checker->checkCsrfToken()
        )
        {
            if ($opt > 2)
                $comments = $entityManager->getRepository(Comment::class)->findBy([], ['id' => 'DESC']);
            else
                $comments = $entityManager->getRepository(Comment::class)->findBy(['validated' => false], ['id' => 'DESC']);
        }
        else
            $comments = $entityManager->getRepository(Comment::class)->findBy(['validated' => true], ['id' => 'DESC']);

        $res = [];
        foreach ($comments as $comment)
        {
            $res[] = [
                'id' => $comment->getId(),
                'poster' => $comment->getPosterName(),
                'note' => $comment->getNote(),
                'comment' => $comment->getComment(),
                'date' => $comment->getDate()->format('d-m-Y'),
            ];
        }

        return new Response(json_encode($res));
    }

    #[Route('/worker/remove_comment')]
    public function removeComment(EntityManagerInterface $entityManager, CheckerInterface $checker): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_WORKER))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);
        if (!$checker->checkArrayData($_POST, 'ids', 'array'))
            return new Response('Bad request', 403);

        $ids = $_POST['ids'];
        $comments = $entityManager->getRepository(Comment::class)->findBy(['id' => $ids]);
        foreach ($comments as $comment)
            $entityManager->remove($comment);
        $entityManager->flush();
        return new Response();
    }

    #[Route('/worker/validate_comment')]
    public function validateComment(EntityManagerInterface $entityManager, CheckerInterface $checker): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_WORKER))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);
        if (!$checker->checkArrayData($_POST, 'ids', 'array'))
            return new Response('Bad request', 403);

        $ids = $_POST['ids'];
        $comments = $entityManager->getRepository(Comment::class)->findBy(['id' => $ids]);
        foreach ($comments as $comment)
            $comment->setValidated(true);
        $entityManager->flush();
        return new Response();
    }

    #[Route('/worker/unvalidate_comment')]
    public function unvalidateComment(EntityManagerInterface $entityManager, CheckerInterface $checker): Response
    {
        if (!$checker->checkUser(RolesInterface::ROLE_WORKER))
            return new Response('Unauthorized', 401);
        if (!$checker->checkCsrfToken())
            return new Response('Bad Csrf Token', 403);
        if (!$checker->checkArrayData($_POST, 'ids', 'array'))
            return new Response('Bad request', 403);

        $ids = $_POST['ids'];
        $comments = $entityManager->getRepository(Comment::class)->findBy(['id' => $ids]);
        foreach ($comments as $comment)
            $comment->setValidated(false);
        $entityManager->flush();
        return new Response();
    }
}
