<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdministrateurController extends BaseController
{
    #[Route('/moncompte', name: 'app_moncompte')]
    public function monCompte(UtilisateurRepository $repository): Response
    {
        $utilisateur = $repository->find($this->idUtilisateur);
        $administrateur = $utilisateur->getAdministrateurs();
        return $this->render('main/administrateur/compte.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'information' => $utilisateur
        ]);
    }
}
