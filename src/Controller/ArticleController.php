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

    #[Route('/article/{id}/edit', name: 'app_article_edit')]
    public function edit(string $id, ArticleRepository $articleRepository, EntityManagerInterface $em): Response
    {
        if (!$id) {
            return $this->redirectToRoute('app_home');
        }

        $article = $articleRepository->findOneBy(['id' => $id]);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('article/edit.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
        ]);
    }

    #[Route('/article/{id}/delete', name: 'app_article_delete')]
    public function delete(string $id, EntityManagerInterface $em, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBy(['id' => $id]);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_home');
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
}
