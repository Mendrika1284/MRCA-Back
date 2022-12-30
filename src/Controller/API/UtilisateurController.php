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

    /**
     * @Route("/visualiserDevisClient/{id}", methods={"GET"})
     */
    public function visualiserDevisClient(int $id, ManagerRegistry $doctrine)
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
                       devis_client.choix_type_travaux as choixTypeTravaux,
                       devis_client.email as emailClient,
                       devis_client.montant as montant,
                       devis_client.date_debut as dateDebut,
                       devis_client.date_fin as dateFin,
                       artisan.id as idArtisan
                FROM devis_client
                JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                JOIN artisan ON devis_client.id_artisan_id = artisan.id 
                WHERE devis_client.id = :idDevis 
            ';
        $stmtForDevisClient = $conn->prepare($sqlForDevisClient);
        $resultSetForDevisClient = $stmtForDevisClient->executeQuery(['idDevis'=> $id]);
        $detailDevis = $resultSetForDevisClient->fetchAllAssociative();
        $error = error_get_last();


        $sqlForDetailClient = '
                SELECT utilisateur.id as idUtilisateur,
                       utilisateur.nom as nomUtilisateur,
                       utilisateur.prenom as prenomUtilisateur,
                       utilisateur.contact as contactUtilisateur
                FROM utilisateur
                WHERE utilisateur.email = :email 
            ';
        $stmtForDetailClient = $conn->prepare($sqlForDetailClient);
        $resultSetForDetailClient = $stmtForDetailClient->executeQuery(['email'=> $detailDevis[0]['emailClient']]);
        $detailClient = $resultSetForDetailClient->fetchAllAssociative();
        $sqlForDetailArtisan = '
                SELECT utilisateur.id as idArtisan,
                       utilisateur.nom as nomArtisan,
                       utilisateur.prenom as prenomArtisan,
                       utilisateur.contact as contactArtisan,
                       utilisateur.email as emailArtisan,
                       artisan.civilite as civiliteArtisan,
                       artisan.status_juridique as statusJuridiqueArtisan,
                       artisan.siret as siretArtisan,
                       artisan.tva as tvaArtisan,
                       artisan.kbis as kbisArtisan,
                       artisan.iban as ibanArtisan,
                       artisan.bic as bicArtisan,
                       artisan.position_x as positionXArtisan,
                       artisan.position_y as positionYArtisan
                FROM utilisateur
                JOIN artisan on artisan.id_utilisateur_id = utilisateur.id
                WHERE artisan.id = :id 
            ';
        $stmtForDetailArtisan = $conn->prepare($sqlForDetailArtisan);
        $resultSetForDetailArtisan = $stmtForDetailArtisan->executeQuery(['id'=> $detailDevis[0]['idArtisan']]);
        $detailArtisan = $resultSetForDetailArtisan->fetchAllAssociative();

        return new JsonResponse(['detailDevis' => $detailDevis,
                                 'detailClient' => $detailClient,
                                 'detailArtisan' => $detailArtisan
        ]);
    }

    /**
     * @Route("/validerDevis/{id}", methods={"PATCH"})
     */
    public function validerDevis(int $id, Request $request, ManagerRegistry $doctrine)
    {
        $devisClient = $doctrine->getRepository(DevisClient::class)->find($id);

        if (!$devisClient) {
            throw new NotFoundHttpException('Devis client non trouvé');
        }

        // Etat validé
        $devisClient->setEtat(3);

        $em = $doctrine->getManager();
        $em->persist($devisClient);
        $em->flush();

        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/refuserDevis/{id}", methods={"PATCH"})
     */
    public function refuserDevis(int $id, Request $request, ManagerRegistry $doctrine)
    {
        $devisClient = $doctrine->getRepository(DevisClient::class)->find($id);

        if (!$devisClient) {
            throw new NotFoundHttpException('Devis client non trouvé');
        }

        // Etat validé
        $devisClient->setEtat(4);

        $em = $doctrine->getManager();
        $em->persist($devisClient);
        $em->flush();

        return new JsonResponse(null, 204);
    }

}
