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

    /**
     * @Route("/allRendezVousArtisan/{id}", methods={"GET"})
     */
    public function allRendezVousArtisan(ManagerRegistry $doctrine, int $id)
    {
    $entityManager = $doctrine->getManager();
    $conn = $entityManager->getConnection();

    $sqlForEvenement = '
    SELECT rendez_vous.id,
           rendez_vous.titre,
           rendez_vous.description,
           rendez_vous.date,
           rendez_vous.heure_debut as heureDebut,
           rendez_vous.heure_fin as heureFin
    FROM rendez_vous
    WHERE rendez_vous.id_artisan_id = :id
    ';
    $stmtForEvenement = $conn->prepare($sqlForEvenement);
    $resultSetForEvenement = $stmtForEvenement->executeQuery(['id'=>$id]);

    $evenement = $resultSetForEvenement->fetchAllAssociative();

    return new JsonResponse(['rendez_vous' => $evenement]);
}

    /**
     * @Route("/rendezVousClientAValider/{id}", methods={"GET"})
     */
    public function rendezVousClientAValider(ManagerRegistry $doctrine, int $id)
    {
    $entityManager = $doctrine->getManager();
    $conn = $entityManager->getConnection();

    $sqlForEvenement = '
    SELECT rendez_vous.id,
           rendez_vous.titre,
           rendez_vous.description,
           rendez_vous.date,
           rendez_vous.heure_debut as heureDebut,
           rendez_vous.heure_fin as heureFin
    FROM rendez_vous
    WHERE rendez_vous.id_utilisateur_id = :id and rendez_vous.etat = 0
    ';
    $stmtForEvenement = $conn->prepare($sqlForEvenement);
    $resultSetForEvenement = $stmtForEvenement->executeQuery(['id'=>$id]);

    $evenement = $resultSetForEvenement->fetchAllAssociative();

    return new JsonResponse(['rendez_vous' => $evenement]);
}

/**
     * @Route("/getRendezVousById/{id}", methods={"GET"})
     */
    public function getRendezVousById(ManagerRegistry $doctrine, int $id)
    {
    $entityManager = $doctrine->getManager();
    $conn = $entityManager->getConnection();

    $sqlForEvenement = '
    SELECT rendez_vous.id,
           rendez_vous.titre,
           rendez_vous.description,
           rendez_vous.date,
           rendez_vous.heure_debut as heureDebut,
           rendez_vous.heure_fin as heureFin
    FROM rendez_vous
    WHERE rendez_vous.id = :id
    ';
    $stmtForEvenement = $conn->prepare($sqlForEvenement);
    $resultSetForEvenement = $stmtForEvenement->executeQuery(['id'=>$id]);

    $evenement = $resultSetForEvenement->fetchAllAssociative();

    return new JsonResponse(['rendez_vous' => $evenement]);
}

    /**
     * @Route("/reporterRDV/{id}", methods={"PATCH"})
     */
    public function reporterRDV(int $id, Request $request, ManagerRegistry $doctrine)
    {
        $data = json_decode($request->getContent(), true);
        $rdv = $doctrine->getRepository(RendezVous::class)->find($id);

        if (!$rdv) {
            throw new NotFoundHttpException('Rendez-vous non trouvé');
        }

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $data['date']);
        $rdv->setDate($date);
        $rdv->setHeureDebut($data["heureDebut"]);
        $rdv->setHeureFin($data["heureFin"]);

        $em = $doctrine->getManager();
        $em->persist($rdv);
        $em->flush();

        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/validerRendezVousByClient/{id}", methods={"PATCH"})
     */
    public function validerRendezVousByClient(int $id, Request $request, ManagerRegistry $doctrine)
    {
        $devisClient = $doctrine->getRepository(RendezVous::class)->find($id);

        if (!$devisClient) {
            throw new NotFoundHttpException('Rendez-vous non trouvé');
        }

        // Etat validé
        $devisClient->setEtat(1);

        $em = $doctrine->getManager();
        $em->persist($devisClient);
        $em->flush();

        return new JsonResponse(null, 204);
    }

    /**
     * @Route("/refuserRendezVousByClient/{id}", methods={"PATCH"})
     */
    public function refuserRendezVousByClient(int $id, Request $request, ManagerRegistry $doctrine)
    {
        $devisClient = $doctrine->getRepository(RendezVous::class)->find($id);

        if (!$devisClient) {
            throw new NotFoundHttpException('Rendez-vous non trouvé');
        }

        // Etat validé
        $devisClient->setEtat(2);

        $em = $doctrine->getManager();
        $em->persist($devisClient);
        $em->flush();

        return new JsonResponse(null, 204);
    }


/**
     * @Route("/getRendezVousFromListeDevis/{id}", methods={"GET"})
     */
    public function getRendezVousFromListeDevis(ManagerRegistry $doctrine, int $id)
    {
    $entityManager = $doctrine->getManager();
    $conn = $entityManager->getConnection();

    $sqlForEvenement = '
    SELECT rendez_vous.id,
           rendez_vous.titre,
           rendez_vous.description,
           rendez_vous.date,
           rendez_vous.heure_debut as heureDebut,
           rendez_vous.heure_fin as heureFin,
           rendez_vous.etat
    FROM rendez_vous
    JOIN devis_client ON devis_client.id = rendez_vous.id_devis_client_id
    WHERE rendez_vous.id_devis_client_id = :id 
    ';
    $stmtForEvenement = $conn->prepare($sqlForEvenement);
    $resultSetForEvenement = $stmtForEvenement->executeQuery(['id'=>$id]);

    $evenement = $resultSetForEvenement->fetchAllAssociative();

    return new JsonResponse(['rendez_vous' => $evenement]);
}


}