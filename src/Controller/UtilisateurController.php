<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Artisan;
use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use App\Form\AjoutAdminType;
use App\Form\AjoutClientType;
use App\Entity\Administrateur;
use App\Form\AjoutArtisanType;
use App\Form\AjoutEntrepriseType;
use App\Form\AjoutUtilisateurType;
use App\Repository\ClientRepository;
use App\Repository\ArtisanRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\AdministrateurRepository;
use App\Repository\CategorieMetierRepository;
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
            5
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
                "Veuillez completer ces informations"
            );

            $manager->persist($utilisateur);
            $manager->flush();
            $role = $form['roles']->getData();

            if(strcmp($role[0], "ROLE_CLIENT") == 0){
                return $this->redirectToRoute('app_ajout_client');
            }else if(strcmp($role[0], "ROLE_ENTREPRISE") == 0){
                return $this->redirectToRoute('app_ajout_entreprise');
            }else if(strcmp($role[0], "ROLE_PROFESSIONNAL") == 0){
                return $this->redirectToRoute('app_ajout_artisan');
            }else if(strcmp($role[0], "ROLE_ADMIN") == 0){
                return $this->redirectToRoute('app_ajout_admin');
            }
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

            return $this->redirectToRoute('app_ajout_utilisateur');
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

            return $this->redirectToRoute('app_ajout_utilisateur');
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

            return $this->redirectToRoute('app_ajout_utilisateur');
        }

        return $this->render('main/utilisateur/ajout/ajoutArtisan.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'form' => $form->createView()
        ]);
    }

    #[Route('/ajout_admin', name: 'app_ajout_admin', methods: ['GET','POST'])]
    public function ajoutAdministrateur(Request $request, EntityManagerInterface $manager){
        $admin = new Administrateur();
        $form = $this->createForm(AjoutAdminType::class, $admin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $admin = $form->getData();

            $this->addFlash(
                'success',
                "Le compte Administrateur a été enregistrer"
            );

            $manager->persist($admin);
            $manager->flush();

            return $this->redirectToRoute('app_ajout_utilisateur');
        }

        return $this->render('main/utilisateur/ajout/ajoutAdmin.html.twig', [
            'nomUtilisateur' => $this->sessionUtilisateur,
            'form' => $form->createView()
        ]);
    }

    #[Route('/voirUtilisateur/{id}', name: 'app_voir_utilisateur', methods: ['GET','POST'])]
    public function voirUtilisateur(int $id,
        UtilisateurRepository $utilisateurRepository,
        EntrepriseRepository $entrepriseRepository,
        ClientRepository $clientRepository,
        ArtisanRepository $artisanRepository,
        AdministrateurRepository $adminRepository,
        CategorieMetierRepository $metierRepository){
        $utilisateur = $utilisateurRepository->find($id);
        
        if(strcmp($utilisateur->getRoles()[0],"ROLE_CLIENT") == 0){
            $client = $clientRepository->findOneBy(['idUtilisateur'=>$id]);
            return $this->render('main/utilisateur/voir.html.twig', [
                'nomUtilisateur' => $this->sessionUtilisateur,
                'role' => "client",
                'utilisateur' => $utilisateur,
                'client' => $client
            ]);
        }else if(strcmp($utilisateur->getRoles()[0],"ROLE_ENTREPRISE") == 0){
            $entreprise = $entrepriseRepository->findOneBy(['idUtilisateur'=>$id]);
            return $this->render('main/utilisateur/voir.html.twig', [
                'nomUtilisateur' => $this->sessionUtilisateur,
                'role' => "entreprise",
                'utilisateur' => $utilisateur,
                'entreprise' => $entreprise
            ]);
        }else if(strcmp($utilisateur->getRoles()[0],"ROLE_PROFESSIONNAL") == 0){
            $artisan = $artisanRepository->findOneBy(['idUtilisateur'=>$id]);
            $metier = $metierRepository->findOneBy(['idUtilisateur'=>$id]);
            return $this->render('main/utilisateur/voir.html.twig', [
                'nomUtilisateur' => $this->sessionUtilisateur,
                'role' => "artisan",
                'utilisateur' => $utilisateur,
                'artisan' => $artisan,
                'metier' => $metier
            ]);
        }else if(strcmp($utilisateur->getRoles()[0],"ROLE_ADMIN") == 0){
            $admin = $adminRepository->findOneBy(['idUtilisateur'=>$id]);
            return $this->render('main/utilisateur/voir.html.twig', [
                'nomUtilisateur' => $this->sessionUtilisateur,
                'role' => "admin",
                'utilisateur' => $utilisateur,
                'admin' => $admin
            ]);
        }
    }

    #[Route('/activerUtilisateur/{id}', name: 'app_activer_utilisateur', methods: ['GET','POST'])]
    public function activerUtilisateur(int $id, ManagerRegistry $doctrine){
        
        $entityManager = $doctrine->getManager();
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($id);

        $utilisateur->setStatusCompte(1);
        $entityManager->flush();

        return $this->redirectToRoute('app_liste_utilisateur');
    }

    #[Route('/desactiverUtilisateur/{id}', name: 'app_desactiver_utilisateur', methods: ['GET','POST'])]
    public function desactiverUtilisateur(int $id, ManagerRegistry $doctrine){
        
        $entityManager = $doctrine->getManager();
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($id);

        $utilisateur->setStatusCompte(0);
        $entityManager->flush();

        return $this->redirectToRoute('app_liste_utilisateur');
    }

}