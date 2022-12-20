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
                SELECT id,email,password,roles,status_compte FROM utilisateur WHERE email = :email AND password = :password LIMIT 1
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
}
