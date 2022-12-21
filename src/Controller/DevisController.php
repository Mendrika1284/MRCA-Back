<?php

namespace App\Controller;

use App\Entity\Artisan;
use App\Entity\DevisClient;
use App\Controller\BaseController;
use App\Repository\ArtisanRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DevisController extends BaseController
{
    #[Route('/assignerDevis/{id}', name: 'app_assigner_devis', methods: ['GET','POST'])]
    public function assignerDevis(int $id, ManagerRegistry $doctrine){

        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForDevisClient = '
                SELECT type_travaux.id as idTypeTravaux,
                       type_travaux.nom as nomTypeTravaux, 
                       devis_client.id as idDevis,
                       devis_client.position_x as devisPositionX,
                       devis_client.position_y as devisPositionY,
                       devis_client.info_supplementaire as detailDevis,
                       devis_client.created_at as dateCreation,
                       devis_client.etat as etatDevis
                FROM devis_client
                JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                WHERE devis_client.id = :idDevis
            ';
        $stmtForDevisClient = $conn->prepare($sqlForDevisClient);
        $resultSetForDevisClient = $stmtForDevisClient->executeQuery(['idDevis'=> $id]);

        $devisClient = $resultSetForDevisClient->fetchAllAssociative();

        $sqlForArtisan = '
                SELECT utilisateur.nom as nomArtisan,
                       utilisateur.prenom as prenomArtisan,
                       utilisateur.contact as contactArtisan,
                       utilisateur.email as emailArtisan,
                       artisan.id as idArtisan,
                       artisan.position_x as positionXArtisan,
                       artisan.position_y as positionYArtisan,
                       artisan.est_occupe as estOccupe
                FROM artisan
                JOIN utilisateur ON utilisateur.id = artisan.id_utilisateur_id
                ORDER BY nomArtisan ASC
            ';
        $stmtForArtisan = $conn->prepare($sqlForArtisan);
        $resultSetForArtisan = $stmtForArtisan->executeQuery();

        $artisan = $resultSetForArtisan->fetchAllAssociative();


        return $this->render('devis/assigner.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'devisClient' => $devisClient,
            'artisans' => $artisan
        ]);
    }

    #[Route('/assigner/{id}/{id2}', name: 'app_assigner')]
    public function assigner(ManagerRegistry $doctrine, int $id, int $id2): Response
    {
        $entityManager = $doctrine->getManager();
        $devisClient = $entityManager->getRepository(DevisClient::class)->find($id);
        $artisan = $entityManager->getRepository(Artisan::class)->find($id2);

        $devisClient->setEtat(1);
        $devisClient->setIdArtisan($artisan);
        $entityManager->flush();
        return $this->redirectToRoute('app_accueil');
    }  

    #[Route('/visualiserDevis/{id}', name: 'app_visualiser_devis', methods: ['GET','POST'])]
    public function visualiserDevis(int $id, ManagerRegistry $doctrine){
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForDevisClient = '
                SELECT type_travaux.id as idTypeTravaux,
                       type_travaux.nom as nomTypeTravaux, 
                       devis_client.id as idDevis,
                       devis_client.position_x as devisPositionX,
                       devis_client.position_y as devisPositionY,
                       devis_client.info_supplementaire as detailDevis,
                       devis_client.created_at as dateCreation,
                       devis_client.etat as etatDevis
                FROM devis_client
                JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                WHERE devis_client.id = :idDevis
            ';
        $stmtForDevisClient = $conn->prepare($sqlForDevisClient);
        $resultSetForDevisClient = $stmtForDevisClient->executeQuery(['idDevis'=> $id]);

        $devisClient = $resultSetForDevisClient->fetchAllAssociative();

        $sqlForArtisan = '
                SELECT utilisateur.nom as nomArtisan,
                       utilisateur.prenom as prenomArtisan,
                       utilisateur.contact as contactArtisan,
                       utilisateur.email as emailArtisan,
                       artisan.id as idArtisan,
                       artisan.position_x as positionXArtisan,
                       artisan.position_y as positionYArtisan,
                       artisan.est_occupe as estOccupe
                FROM artisan
                JOIN utilisateur ON utilisateur.id = artisan.id_utilisateur_id
                ORDER BY nomArtisan ASC
            ';
        $stmtForArtisan = $conn->prepare($sqlForArtisan);
        $resultSetForArtisan = $stmtForArtisan->executeQuery();

        $artisan = $resultSetForArtisan->fetchAllAssociative();


        return $this->render('devis/visualiser.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'devisClient' => $devisClient,
            'artisans' => $artisan
        ]);
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
