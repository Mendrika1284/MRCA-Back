<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Artisan;
use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use App\Form\AjoutClientType;
use App\Form\AjoutArtisanType;
use App\Form\AjoutEntrepriseType;
use App\Form\AjoutUtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UtilisateurController extends baseController
{
    #[Route('/liste_utilisateur', name: 'app_liste_utilisateur')]
    public function index(UtilisateurRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $utilisateur = $repository->findAll();

        $pagination = $paginator->paginate(
            $utilisateur,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('main/utilisateur/liste.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'listeUtilisateur' => $pagination
        ]);
    }

    #[Route('/ajout_utilisateur', name: 'app_ajout_utilisateur', methods: ['GET','POST'])]
    public function ajoutUtilisateur(Request $request, EntityManagerInterface $manager){
        $utilisateur = new Utilisateur();
        $form = $this->createForm(AjoutUtilisateurType::class, $utilisateur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $utilisateur = $form->getData();

            $this->addFlash(
                'success',
                "Le compte a été enregistrer"
            );

            $manager->persist($utilisateur);
            $manager->flush();

            return $this->redirectToRoute('app_ajout_utilisateur');
        }

        return $this->render('main/utilisateur/ajout/ajoutUtilisateur.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'form' => $form->createView()
        ]);
    }

    #[Route('/ajout_client', name: 'app_ajout_client', methods: ['GET','POST'])]
    public function ajoutClient(Request $request, EntityManagerInterface $manager){
        $client = new Client();
        $form = $this->createForm(AjoutClientType::class, $client);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $client = $form->getData();

            $this->addFlash(
                'success',
                "Le compte client a été enregistrer"
            );

            $manager->persist($client);
            $manager->flush();

            return $this->redirectToRoute('app_ajout_client');
        }

        return $this->render('main/utilisateur/ajout/ajoutClient.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'form' => $form->createView()
        ]);
    }

    #[Route('/ajout_entreprise', name: 'app_ajout_entreprise', methods: ['GET','POST'])]
    public function ajoutEntreprise(Request $request, EntityManagerInterface $manager){
        $entreprise = new Entreprise();
        $form = $this->createForm(AjoutEntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $client = $form->getData();

            $this->addFlash(
                'success',
                "Le compte entreprise a été enregistrer"
            );

            $manager->persist($entreprise);
            $manager->flush();

            return $this->redirectToRoute('app_ajout_entreprise');
        }

        return $this->render('main/utilisateur/ajout/ajoutEntreprise.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'form' => $form->createView()
        ]);
    }


    #[Route('/ajout_professionnel', name: 'app_ajout_artisan', methods: ['GET','POST'])]
    public function ajoutProfessionnel(Request $request, EntityManagerInterface $manager){
        $artisan = new Artisan();
        $form = $this->createForm(AjoutArtisanType::class, $artisan);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $artisan = $form->getData();

            $this->addFlash(
                'success',
                "Le compte professionnel a été enregistrer"
            );

            $manager->persist($artisan);
            $manager->flush();

            return $this->redirectToRoute('app_ajout_artisan');
        }

        return $this->render('main/utilisateur/ajout/ajoutArtisan.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'form' => $form->createView()
        ]);
    }

}