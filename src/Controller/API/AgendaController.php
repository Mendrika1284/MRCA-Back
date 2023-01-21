<?php

namespace App\Controller\API;

use App\Entity\Artisan;
use App\Entity\Evenement;
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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AgendaController extends AbstractController
{
    /**
     * @Route("/ajouterEvenement", methods={"POST"})
     */
    public function ajouterEvenement(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        $evenement = $data['evenement'];
        $date = $data['date'];
        $heureDebut = $data['heureDebut'];
        $heureFin = $data['heureFin'];
        
        $dataObject = new Evenement();
        $idUtilisateur = $data['idUtilisateur'];
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['id' => $idUtilisateur]);
        
        $dateEvent = new \DateTime($date);

        $dataObject->setIdUtilisateur($utilisateur);
        $dataObject->setEvenement($evenement);
        $dataObject->setDate($dateEvent);
        $dataObject->setHeureDebut($heureDebut);
        $dataObject->setHeureFin($heureFin);
        
        $entityManager->persist($dataObject);
        $entityManager->flush();
        
        return new JsonResponse(['status' => 'success']);        
    }

    /**
     * @Route("/enleverEvenement/{id}", methods={"DELETE"})
     */
    public function enleverEvenement($id, ManagerRegistry $doctrine)
    {
        $entity = $doctrine->getRepository(Evenement::class)->findOneBy(['id'=>$id]);
        $em = $doctrine->getManager();
        $em->remove($entity);
        $em->flush();

        return new Response(null, Response::HTTP_OK);
    }


    /**
     * @Route("/allEvenement/{id}", methods={"GET"})
     */
    public function allEvenement(ManagerRegistry $doctrine, int $id)
    {
    $entityManager = $doctrine->getManager();
    $conn = $entityManager->getConnection();

    $sqlForEvenement = '
    SELECT evenement.id,
           evenement.evenement,
           evenement.date,
           evenement.heure_debut as heureDebut,
           evenement.heure_fin as heureFin
    FROM evenement
    WHERE id_utilisateur_id = :id
    ';
    $stmtForEvenement = $conn->prepare($sqlForEvenement);
    $resultSetForEvenement = $stmtForEvenement->executeQuery(['id'=>$id]);

    $evenement = $resultSetForEvenement->fetchAllAssociative();

    return new JsonResponse(['evenement' => $evenement]);
}



}