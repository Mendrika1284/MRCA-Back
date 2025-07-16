<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use App\Repository\InterventionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterventionController extends baseController
{

    #[Route('/liste_intervention', name: 'app_liste_intervention')]
    public function index(InterventionRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $intervention = $repository->findAll();

        $pagination = $paginator->paginate(
            $intervention,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('intervention/liste.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'listeIntervention' => $pagination
        ]);
    }

    #[Route('/visualiserIntervention/{id}', name: 'app_visualiser_intervention', methods: ['GET','POST'])]
    public function visualiserIntervention(int $id, ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForIntervention = '
                SELECT 
                    intervention.id as idIntervention,
                    devis_client.date_debut as dateDebut,
                    devis_client.date_fin as dateFin,
                    utilisateur.nom as nomUtilisateur,
                    utilisateur.prenom as prenomUtilisateur,
                    devis_client.position_x as positionX,
                    devis_client.position_y as positionY,
                    devis_client.info_supplementaire as detailsDemandeClient,
                    devis_client.choix_type_travaux as typeTravaux,
                    type_travaux.nom as categorieTravaux,
                    devis_client.montant as montant,
                    intervention.etat as etatIntervention
                FROM intervention
                JOIN devis_client ON intervention.id_devis_client_id = devis_client.id
                JOIN utilisateur ON intervention.id_utilisateur_id = utilisateur.id
                JOIN type_travaux ON devis_client.id_type_travaux_id = type_travaux.id
                WHERE intervention.id = :idIntervention
            ';
        $stmtForIntervention = $conn->prepare($sqlForIntervention);
        $resultSetForIntervention = $stmtForIntervention->executeQuery(['idIntervention'=> $id]);

        $intervention = $resultSetForIntervention->fetchAllAssociative();

        return $this->render('intervention/visualiser.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'intervention' => $intervention
        ]);
    }
}
