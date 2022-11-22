<?php

namespace App\Controller;

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
    public function logout(): Response
    {}    

    #[Route('/', name: 'app_accueil')]
    public function index(UserInterface $user, Request $request): Response
    {
        if(!empty($user) && $user->getRoles()[0] == "ROLE_ADMIN"){
            $userName = $user->getNom();
            $userId = $user->getId();
            $session = $this->requestStack->getSession();
            $session->set('nomUtilisateur', $userName);
            $session->set('idUtilisateur', $userId);
            return $this->render('main/index.html.twig', [
                'controller_name' => 'BackController',
                'nomUtilisateur' => $session->get('nomUtilisateur'),
                'idUtilisateur' =>$session->get('idUtilisateur')
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
