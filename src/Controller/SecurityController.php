<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Etudiant;
use App\Entity\Enseignant;
use App\Entity\User;

class SecurityController extends AbstractController
{
    
    public function index(): Response
    {
        return $this->redirectToRoute('app_login');
    }
    
    
    
    /**
     * Méthode d'authentification
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        //return $this->render('security/login.html.twig', ['error' => $error]);
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Méthode redirigeant vers la page d'accueil selon rôle user après authentification
     */
    public function home()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $statut = $this->getUser()->getStatut();
        if ($statut == 'AC')
        {
            $role = $this->getUser()->getRoles()[0];
            if ($role == 'ROLE_ENSEIGNANT'){   

                return $this->render('enseignant/home.html.twig', [
                'enseignant' => $this->getUser()->getEnseignant(),
                 ]);
            }
            if ($role == 'ROLE_ETUDIANT'){
           
                return $this->render('etudiant/home.html.twig', [
                'etudiant' => $this->getUser()->getEtudiant(),
                 ]);
            }
            if ($role == 'ROLE_ADMIN'){
                return $this->render('admin/home.html.twig');  
            }
        }
    else
     {
        return $this->render('register/userNonValide.html.twig', [
            'pEtudiant' =>  $this->getUser()->getEtudiant(),
            ]);
     }
  
    }


}
