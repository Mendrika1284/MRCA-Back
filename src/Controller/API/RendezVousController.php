<?php

namespace App\Controller\API;

use App\Entity\Artisan;
use App\Entity\RendezVous;
use App\Entity\DevisClient;
use App\Entity\Utilisateur;
use App\Entity\Intervention;
use App\Entity\DisponibiliteArtisan;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RendezVousController extends AbstractController
{
    /**
     * @Route("/detailUtilisateur/{email}", methods={"GET"})
     */
    public function detailUtilisateur(ManagerRegistry $doctrine, String $email)
    {
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForDetailUtilisateur = '
        SELECT utilisateur.id as idUtilisateur,
               utilisateur.nom as nomUtilisateur,
               utilisateur.prenom as prenomUtilisateur,
               utilisateur.contact as contactUtilisateur
        FROM utilisateur
        WHERE utilisateur.email = :email
    ';
    $stmtForDetailUtilisateur = $conn->prepare($sqlForDetailUtilisateur);
    $resultSetForDetailUtilisateur = $stmtForDetailUtilisateur->executeQuery(['email'=> $email]);

    $detailUtilisateur = $resultSetForDetailUtilisateur->fetchAllAssociative();

        return new JsonResponse(['detailUtilisateur' => $detailUtilisateur]);
    }

    /**
     * @Route("/creerRendezVous", methods={"GET", "POST"})
     */
    public function creerRendezVous(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        $idUtilisateur = $data['idUtilisateur'];
        $dataObject = $entityManager->getRepository(RendezVous::class)->findOneBy(['idUtilisateur' => $idUtilisateur]);
        if (!$dataObject) {
            $dataObject = new RendezVous();
        }
        
        $idArtisan = $data['idArtisan'];
        $artisan = $entityManager->getRepository(Artisan::class)->find($idArtisan);
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['id' => $idUtilisateur]);
        $idDevisClient =  $data['idDevisClient'];
        $devisClient = $entityManager->getRepository(DevisClient::class)->find($idDevisClient);
        $date = $data['date'];
        $dateRDV = new \DateTime($date);
        $heureDebut = $data['heureDebut'];
        $heureFin = $data['heureFin'];
        $etat = $data['etat'];
        $titre = $data['titre'];
        $description = $data['description'];
        
        $dataObject->setIdArtisan($artisan);
        $dataObject->setIdUtilisateur($utilisateur);
        $dataObject->setIdDevisClient($devisClient);
        $dataObject->setDate($dateRDV);
        $dataObject->setHeureDebut($heureDebut);
        $dataObject->setHeureFin($heureFin);
        $dataObject->setEtat($etat);
        $dataObject->setTitre($titre);
        $dataObject->setDescription($description);
        
        $entityManager->persist($dataObject);
        $entityManager->flush();
        
        return new JsonResponse(['status' => 'success']);        
    }


}