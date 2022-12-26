<?php

namespace App\Controller\API;

use App\Entity\DevisClient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DevisClientApiController extends AbstractController
{
    /**
     * @Route("/listeDevisParArtisan/{id}", methods={"GET"})
     */
    public function listeDevisParArtisan(int $id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForDevisClient = '
                SELECT type_travaux.id as idTypeTravaux,
                       type_travaux.nom as nomTypeTravaux, 
                       devis_client.id as idDevis,
                       devis_client.created_at as dateCreation,
                       devis_client.etat as etatDevis,
                       devis_client.email as emailClient
                FROM devis_client
                JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                JOIN artisan ON devis_client.id_artisan_id = artisan.id 
                JOIN utilisateur ON artisan.id_utilisateur_id = utilisateur.id
                WHERE utilisateur.id = :idArtisan 
            ';
        $stmtForDevisClient = $conn->prepare($sqlForDevisClient);
        $resultSetForDevisClient = $stmtForDevisClient->executeQuery(['idArtisan'=> $id]);

        $devisArtisan = $resultSetForDevisClient->fetchAllAssociative();

        return new JsonResponse(['devisArtisan' => $devisArtisan]);
    }

    /**
     * @Route("/visualiserDevisArtisan/{id}", methods={"GET"})
     */
    public function visualiserDevisArtisan(int $id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForDevisClient = '
                SELECT type_travaux.id as idTypeTravaux,
                       type_travaux.nom as nomTypeTravaux, 
                       devis_client.id as idDevis,
                       devis_client.created_at as dateCreation,
                       devis_client.position_x as devisPositionX,
                       devis_client.position_y as devisPositionY,
                       devis_client.info_supplementaire as detailDevis,
                       devis_client.etat as etatDevis,
                       devis_client.email as emailClient,
                       devis_client.montant as montant
                FROM devis_client
                JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                JOIN artisan ON devis_client.id_artisan_id = artisan.id 
                WHERE devis_client.id = :idDevis 
            ';
        $stmtForDevisClient = $conn->prepare($sqlForDevisClient);
        $resultSetForDevisClient = $stmtForDevisClient->executeQuery(['idDevis'=> $id]);
        $detailDevis = $resultSetForDevisClient->fetchAllAssociative();

        $sqlForDetailUtilisateur = '
                SELECT utilisateur.id as idUtilisateur,
                       utilisateur.nom as nomUtilisateur,
                       utilisateur.prenom as prenomUtilisateur,
                       utilisateur.contact as contactUtilisateur
                FROM utilisateur
                WHERE utilisateur.email = :email 
            ';
        $stmtForDetailUtilisateur = $conn->prepare($sqlForDetailUtilisateur);
        $resultSetForDetailUtilisateur = $stmtForDetailUtilisateur->executeQuery(['email'=> $detailDevis[0]['emailClient']]);

        $detailUtilisateur = $resultSetForDetailUtilisateur->fetchAllAssociative();

        return new JsonResponse(['detailDevis' => $detailDevis,
                                 'detailUtilisateur' => $detailUtilisateur   
        ]);
    }

    /**
     * @Route("/visualiserDevisArtisanApreparer/{id}", methods={"GET"})
     */
    public function visualiserDevisArtisanApreparer(int $id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForDevisClient = '
                SELECT type_travaux.id as idTypeTravaux,
                       type_travaux.nom as nomTypeTravaux, 
                       devis_client.id as idDevis,
                       devis_client.created_at as dateCreation,
                       devis_client.position_x as devisPositionX,
                       devis_client.position_y as devisPositionY,
                       devis_client.info_supplementaire as detailDevis,
                       devis_client.etat as etatDevis,
                       devis_client.email as emailClient,
                       devis_client.montant as montant,
                       devis_client.date_debut as dateDebut,
                       devis_client.date_fin as dateFin
                FROM devis_client
                JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                JOIN artisan ON devis_client.id_artisan_id = artisan.id 
                WHERE devis_client.id = :idDevis 
            ';
        $stmtForDevisClient = $conn->prepare($sqlForDevisClient);
        $resultSetForDevisClient = $stmtForDevisClient->executeQuery(['idDevis'=> $id]);
        $detailDevis = $resultSetForDevisClient->fetchAllAssociative();

        $sqlForDetailUtilisateur = '
                SELECT utilisateur.id as idUtilisateur,
                       utilisateur.nom as nomUtilisateur,
                       utilisateur.prenom as prenomUtilisateur,
                       utilisateur.contact as contactUtilisateur
                FROM utilisateur
                WHERE utilisateur.email = :email 
            ';
        $stmtForDetailUtilisateur = $conn->prepare($sqlForDetailUtilisateur);
        $resultSetForDetailUtilisateur = $stmtForDetailUtilisateur->executeQuery(['email'=> $detailDevis[0]['emailClient']]);

        $detailUtilisateur = $resultSetForDetailUtilisateur->fetchAllAssociative();

        return new JsonResponse(['detailDevis' => $detailDevis,
                                 'detailUtilisateur' => $detailUtilisateur   
        ]);
    }


    /**
     * @Route("/preparerDevis/{id}/{montant}", methods={"PATCH"})
     */
    public function preparerDevis(int $id, String $montant, Request $request, ManagerRegistry $doctrine)
    {
        $devisClient = $doctrine->getRepository(DevisClient::class)->find($id);

        if (!$devisClient) {
            throw new NotFoundHttpException('Devis client non trouvé');
        }

        // Update the article properties as needed
        $devisClient->setMontant($montant);
        $devisClient->setEtat(2);

        // Save the changes to the database
        $em = $doctrine->getManager();
        $em->persist($devisClient);
        $em->flush();

        return new JsonResponse(null, 204);
    }
}
