<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{id}/detail', name: 'app_article')]
    public function index(string $id, ArticleRepository $articleRepository): Response
    {
        if (!$id) {
            return $this->redirectToRoute('app_home');
        }

        $article = $articleRepository->findOneBy(['id' => $id]);

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
        ]);
    }

    #[Route('/article/{id}/edit', name: 'article_edit')]
    public function edit(string $id, ArticleRepository $articleRepository, Security $security, Request $request, EntityManagerInterface $em): Response
    {
        if (!$id) {
            return $this->redirectToRoute('app_home');
        }

        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $article = $articleRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('user_articles', ['id' => $user->getId()]);
        }

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('article/edit.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}/delete', name: 'article_delete')]
    public function delete(string $id, EntityManagerInterface $em, ArticleRepository $articleRepository, Security $security): Response
    {
        $article = $articleRepository->findOneBy(['id' => $id]);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        if($article->getUser()->getId()->toString() !== $security->getUser()->getId()->toString())
        {
            return $this->redirectToRoute('app_home');
        }

        $user = $article->getUser();

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('user_articles', [
            'id' => $user->getId(),
        ]);
    }

    #[Route('article/create', name:'article_create')]
    public function create(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $article = new Article();

        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $article->setUser($user);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('article/create.html.twig', [
            'controller_name' => 'ArticleController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/user/{id}', name: 'user_articles')]
    public function userArticles(string $id, ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        if (!$user || $user->getId()->toString() !== $id) {
            return $this->redirectToRoute('app_home');
        }

        $articles = $articleRepository->findBy(['user' => $user]);

        return $this->render('article/user.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
        ]);
    }
}
