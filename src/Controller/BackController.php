<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        return $this->render('login.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }
}
