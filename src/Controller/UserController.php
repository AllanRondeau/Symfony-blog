<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

    #[Route('/user/{id}', name:"app_user_profile")]
    public function index(string $id, UserRepository $userRepository): Response
    {
        if (!$id) {
            return $this->redirectToRoute('app_home');
        }

        $user = $userRepository->findOneBy(['id' => $id]);
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function update(string $id, UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    {
        if (!$id) {
            return $this->redirectToRoute('app_home');
        }

        $user = $userRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()->ToString()]);
        }
        return $this->render('user/edit.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
