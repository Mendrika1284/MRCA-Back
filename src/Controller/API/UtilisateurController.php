<?php

namespace App\Controller\API;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/connexion_utilisateur/{email}/{password}", methods={"POST"})
     */
    public function connexionUtilisateur(TokenGeneratorInterface $tokenGenerator,String $email, String $password, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForConnexion = '
                SELECT id,nom,prenom,email,password,roles,status_compte FROM utilisateur WHERE email = :email AND password = :password LIMIT 1
            ';
        $stmtForClient = $conn->prepare($sqlForConnexion);
        $resultSetForClient = $stmtForClient->executeQuery(['email'=> $email, 'password' => $password]);

        $connexionClient = $resultSetForClient->fetchAllAssociative();
        if(empty($connexionClient[0])){
            return new JsonResponse(['erreur' => "Erreur de connexion"]);
        }else{
            $token = $tokenGenerator->generateToken();
            return new JsonResponse(['connexionClient' => $connexionClient, 'token' => $token]);
        }
    }

    /**
     * @Route("/receptionDevis/{email}", methods={"GET"})
     */
    public function receptionDevis(ManagerRegistry $doctrine, String $email)
    {
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForConnexion = '
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
                            devis_client.date_fin as dateFin,
                            devis_client.choix_type_travaux as choixTypeTravaux
                    FROM devis_client
                    JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                    WHERE devis_client.email = :email
                    GROUP BY idDevis DESC
            ';
        $stmtForDevis = $conn->prepare($sqlForConnexion);
        $resultSetForDevis = $stmtForDevis->executeQuery(['email'=> $email]);

        $devisClient = $resultSetForDevis->fetchAllAssociative();
        return new JsonResponse(['devisClient' => $devisClient]);
    }
}
