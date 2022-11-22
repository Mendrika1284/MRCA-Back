<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected $requestStack;
    protected $sessionUtilisateur;
    protected $idUtilisateur;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        $session = $this->requestStack->getSession();
        $this->sessionUtilisateur = $session->get('nomUtilisateur');
        $this->idUtilisateur = $session->get('idUtilisateur');
        if(empty( $this->idUtilisateur = $session->get('idUtilisateur'))){
            return $this->redirectToRoute('app.login');
        }
    }
}
