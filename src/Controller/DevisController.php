<?php

namespace App\Controller;

use App\Controller\BaseController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DevisController extends BaseController
{
    #[Route('/assignerDevis/{id}', name: 'app_assigner_devis', methods: ['GET','POST'])]
    public function assignerDevis(int $id, ManagerRegistry $doctrine){
        
        $entityManager = $doctrine->getManager();
        $utilisateur = $entityManager->getRepository(DevisClient::class)->find($id);

        $utilisateur->setEtat(1);
        $entityManager->flush();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'BackController',
            'nomUtilisateur' => $session->get('nomUtilisateur'),
            'idUtilisateur' =>$session->get('idUtilisateur'),
            'devisClient' => $pagination,
            'pagination' => $pagination
        ]);
    }

    #[Route('/visualiserDevis/{id}', name: 'app_visualiser_devis', methods: ['GET','POST'])]
    public function visualiserDevis(int $id, ManagerRegistry $doctrine){
        
        $entityManager = $doctrine->getManager();
        $utilisateur = $entityManager->getRepository(DevisClient::class)->find($id);

        $utilisateur->setEtat(1);
        $entityManager->flush();

        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/telechargerDevis/{id}', name: 'app_telecharger_devis', methods: ['GET','POST'])]
    public function telechargerDevis(int $id, ManagerRegistry $doctrine){
        
        $entityManager = $doctrine->getManager();
        $utilisateur = $entityManager->getRepository(DevisClient::class)->find($id);

        $utilisateur->setEtat(1);
        $entityManager->flush();

        return $this->redirectToRoute('app_accueil');
    }
}
