<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
/*#[Route('/registerfront', name: 'registerfront', methods: 'POST')]
    public function inscriptionFront(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $civilite = $decoded->civilite;
        $nom = $decoded->nom;
        $prenom = $decoded->prenom;
        $email = $decoded->email;
        $contact = $decoded->contact;
        $adresse = $decoded->adresse;
        $photo = $decoded->photo;
        $date = $decoded->dateCreation;
        $statusCompte = $decoded->statusCompte;
        $plaintextPassword = $decoded->password;

        $user = new Client();
        $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
        $user->setCivilite($civilite);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setContact($contact);
        $user->setAdresse($adresse);
        $user->setPhoto($photo);
        $user->setDateCreation($date);
        $user->setStatusCompte($statusCompte);

        $em->persist($user);
        $em->flush();

        return $this->json('Client Enregistrer', 200);
    }*/
}
