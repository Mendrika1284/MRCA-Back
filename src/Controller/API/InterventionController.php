<?php

namespace App\Controller\API;

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

class InterventionController extends AbstractController
{
    /**
     * @Route("/listeInterventionRenovation/{id}", methods={"GET"})
     */
    public function listeInterventionRenovation(ManagerRegistry $doctrine, int $id)
    {
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForConnexion = '
                    SELECT type_travaux.id as idTypeTravaux,
                            type_travaux.nom as nomTypeTravaux, 
                            devis_client.id as idDevis,
                            devis_client.created_at as dateCreation,
                            intervention.etat as etatIntervention
                    FROM intervention
                    JOIN devis_client ON intervention.id_devis_client_id = devis_client.id
                    JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                    WHERE intervention.id_artisan_id = :id AND devis_client.choix_type_travaux="Rénovation"
                    GROUP BY idDevis DESC
            ';
        $stmtForRenovation = $conn->prepare($sqlForConnexion);
        $resultSetForRenovation = $stmtForRenovation->executeQuery(['id'=> $id]);

        $renovation = $resultSetForRenovation->fetchAllAssociative();
        return new JsonResponse(['listeInterventionRenovation' => $renovation]);
    }

    /**
     * @Route("/listeInterventionMaintenance/{id}", methods={"GET"})
     */
    public function listeInterventionMaintenance(ManagerRegistry $doctrine, int $id)
    {
        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sqlForConnexion = '
                    SELECT type_travaux.id as idTypeTravaux,
                            type_travaux.nom as nomTypeTravaux, 
                            devis_client.id as idDevis,
                            devis_client.created_at as dateCreation,
                            intervention.etat as etatIntervention
                    FROM intervention
                    JOIN devis_client ON intervention.id_devis_client_id = devis_client.id
                    JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                    WHERE intervention.id_artisan_id = :id AND devis_client.choix_type_travaux="Maintenance"
                    GROUP BY idDevis DESC
            ';
        $stmtForRenovation = $conn->prepare($sqlForConnexion);
        $resultSetForRenovation = $stmtForRenovation->executeQuery(['id'=> $id]);

        $renovation = $resultSetForRenovation->fetchAllAssociative();
        return new JsonResponse(['listeInterventionMaintenance' => $renovation]);
    }

    /**
     * @Route("/planifierTemps/{id}", methods={"POST"})
     */
    public function planifierTemps(Request $request,ManagerRegistry $doctrine, int $id)
    {
        $data = json_decode($request->getContent(), true);

        // Retrieve the existing record, if it exists
        $timeRecord = $doctrine->getRepository(DisponibiliteArtisan::class)
                               ->findOneBy(['idDevisClient' => $id]);

        // If the record does not exist, create a new one
        if (!$timeRecord) {
            $timeRecord = new DisponibiliteArtisan();
            $devisClient = $doctrine->getRepository(DevisClient::class)->find($id);
            $timeRecord->setIdDevisClient($devisClient);
        }

        $utilisateur = $doctrine->getRepository(Utilisateur::class)
        ->find($data['idUtilisateur']);

        // Update the data on the record
        $timeRecord->setIdUtilisateur($utilisateur);
        $timeRecord->setHeureDebut($data['heureDebut']);
        $timeRecord->setHeureFin($data['heureFin']);
        $timeRecord->setJour($data['jour']);

        // Save the record to the database
        $entityManager = $doctrine->getManager();
        $entityManager->persist($timeRecord);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success']);
    }

    /**
     * @Route("/voirTempsConfigurer/{id}", methods={"GET"})
     */
    public function voirTempsConfigurer(ManagerRegistry $doctrine, int $id)
    {
        $repository = $doctrine->getRepository(DisponibiliteArtisan::class);
    
        $qb = $repository->createQueryBuilder('da')
            ->select('da.jour', 'da.heureDebut', 'da.heureFin')
            ->where('da.idDevisClient = :idDevis')
            ->setParameter('idDevis', $id);
    
        $detailDevis = $qb->getQuery()->getResult();
    
        return new JsonResponse(['detailDevis' => $detailDevis]);
    }
}
