<?php

namespace App\Controller\API;

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
}
