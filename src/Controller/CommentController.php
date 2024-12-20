<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Users;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{

    #[Route('/comment/create/{articleId}/{userId}', name:"comment_create")]
    public function create(string $articleId, string $userId, EntityManagerInterface $em, Request $request): Response
    {
        if(!$articleId || !$userId)
        {
            return $this->redirectToRoute('app_home');
        }
        $article = $em->getRepository(Article::class)->findOneBy(['id' => $articleId]);
        $user = $em->getRepository(Users::class)->findOneBy(['id' => $userId]);

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $comment->setUser($user);
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_article', ['id' => $article->getId()]);
        }
        return $this->render('comment/create.html.twig', [
            'controller_name' => 'CommentController',
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('comment/{id}/delete', name: 'comment_delete')]
    public function delete(string $id, EntityManagerInterface $em, Security $security): Response
    {
        $comment = $em->getRepository(Comment::class)->findOneBy(['id' => $id]);

        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }

        if($comment->getUser()->getId()->toString() !== $security->getUser()->getId()->toString())
        {
            return $this->redirectToRoute('app_home');
        }

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('app_article', ['id' => $comment->getArticle()->getId()]);
    }

    #[Route('/comment/{id}/edit', name: 'comment_edit')]
    public function edit(string $id, EntityManagerInterface $em, Request $request, Security $security): Response
    {
        $comment = $em->getRepository(Comment::class)->findOneBy(['id' => $id]);

        if (!$comment) {
            throw $this->createNotFoundException('Comment not found');
        }

        if($comment->getUser()->getId()->toString() !== $security->getUser()->getId()->toString())
        {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_article', ['id' => $comment->getArticle()->getId()]);
        }
        return $this->render('comment/edit.html.twig', [
            'controller_name' => 'CommentController',
            'comment' => $comment,
            'form' => $form->createView(),
            'article' => $comment->getArticle(),
        ]);
    }
}
