<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Controller\BaseController;
use App\Repository\DevisClientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class BackController extends BaseController
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    #[Route('/login', name: 'security.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'security.logout')]
    public function logout(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($this->idUtilisateur);

        $utilisateur->setStatusCompte(0);
        $entityManager->flush();
    }    

    #[Route('/', name: 'app_accueil')]
    public function index(UserInterface $user, Request $request, ManagerRegistry $doctrine, PaginatorInterface $paginator,): Response
    {
        if(!empty($user) && $user->getRoles()[0] == "ROLE_ADMIN"){
            $userName = $user->getNom();
            $userId = $user->getId();
            $session = $this->requestStack->getSession();
            $session->set('nomUtilisateur', $userName);
            $session->set('idUtilisateur', $userId);

            $entityManager = $doctrine->getManager();
            $conn = $entityManager->getConnection();

            $sql = '
                    SELECT type_travaux.id as idTypeTravaux,
                           type_travaux.nom as nomTypeTravaux, 
                           devis_client.id as idDevis,
                           devis_client.created_at as dateCreation,
                           devis_client.etat as etatDevis
                    FROM devis_client
                    JOIN type_travaux ON type_travaux.id = devis_client.id_type_travaux_id
                    ORDER BY dateCreation DESC
                ';
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery();

            $devisClient = $resultSet->fetchAllAssociative();

            $pagination = $paginator->paginate(
                $devisClient,
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('main/index.html.twig', [
                'controller_name' => 'BackController',
                'nomUtilisateur' => $session->get('nomUtilisateur'),
                'idUtilisateur' =>$session->get('idUtilisateur'),
                'devisClient' => $pagination,
                'pagination' => $pagination
            ]);
        }else{
            $this->addFlash(
                'warning',
                "Veuillez vous connecter"
            );
            return $this->redirectToRoute('security.logout');
        }
    }

}