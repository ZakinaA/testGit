MODIF CONTROLEER


<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Promotion;
use App\Entity\Etudiant;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserEnseignantType;
use App\Form\UserEtudiantType;

class AdminController extends AbstractController
{
    
    /**
    * Méthode permettant l'inscription d'un enseignant
    */
    public function addUserEnseignant(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
		//$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $userEnseignant = new user();
		$form = $this->createForm(UserEnseignantType::class, $userEnseignant);
        $form->handleRequest($request);

        // on cherche si un user a déjà été enregistré avec cet email 
        $emailSaisi = $form->get('email')->getData();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $userSearch = $repository->findOneBy(['email'=>$emailSaisi]);
        if( $userSearch != null){
            return $this->render('error/emailExistant.html.twig');
        }

        if ($form->isSubmitted() && $form->isValid()) 
        {

            $userEnseignant = $form->getData();
            $userEnseignant->setPassword('1234abcd');
            $userEnseignant->setStatut('AC');
            $roles[] = 'ROLE_ENSEIGNANT';
            $userEnseignant ->setRoles($roles);
            $userEnseignant->setPassword(
                $passwordEncoder->encodePassword(
                    $userEnseignant,
                    $userEnseignant->getPassword()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userEnseignant);
            $entityManager->flush();
            $this->addFlash('succès', 'inscription enseignant ok');
            return $this->redirectToRoute('listEnseignants');
                       
	    }
        else{

            return $this->render('enseignant/registerEnseignant.html.twig', array(
                'form' => $form->createView(),  'templateTwigParent' => 'baseAdmin.html.twig'));   
        }   
    }

    /**
    * Méthode listant les enseignants
    */
    public function listEnseignants(): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $usersEnseignant = $repository->findEnseignantsActifs();     
        return $this->render('admin/listEnseignants.html.twig', ['ue' => $usersEnseignant]);
      
    }

    /**
    * consultation et édition d'un enseignant
    */
    public function showEditEnseignant(Request $request, $idUser): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $userEnseignant = $repository->find($idUser);

        if (!$userEnseignant) {
	        throw $this->createNotFoundException('Aucun enseignant trouvé avec le numéro user '.$id);
	    }
	    else
	    {   
     
            $form = $this->createForm(UserEnseignantType::class, $userEnseignant , ['champDesactive' => false,]);
            $form->handleRequest($request);
 
            if ($form->isSubmitted() && $form->isValid()) {
 
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($userEnseignant);
                 $entityManager->flush();
                 $this->addFlash('succès', 'modifications enseignant ok');
                 return $this->redirectToRoute('listEnseignants');
           }
           else
           {
                return $this->render('enseignant/registerEnseignant.html.twig', array(
                'form' => $form->createView(),  'templateTwigParent' => 'baseAdmin.html.twig'));   
            }   
           
        }
      
    }
    
     /**
     * Liste les étudiants des deux dernières promotions
     * donc celles avec le statut à AC
     */
    public function listPromoEtudiants(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Promotion::class);
        $promos = $repository->findBy(['statut' => 'AC']);
        
        return $this->render('admin/listPromoEtudiants.html.twig', [
            'promos' => $promos,
        ]);
    }

    /**
     * Admin : consultation / édition d'un étudiant
     */    
    public function showEditEtudiant(Request $request, $idEtudiant): Response
    {
        $repository = $this->getDoctrine()->getRepository(Etudiant::class);
        $etudiant = $repository->find($idEtudiant);

        $repository = $this->getDoctrine()->getRepository(User::class);
        $userEtudiant = $repository->findOneBy(['etudiant'=>$etudiant]);
        
        if (!$userEtudiant) {
            throw $this->createNotFoundException('Aucun etudiant trouvé avec le numéro '.$idEtudiant);
        }
        else
        {
                $form = $this->createForm(UserEtudiantType::class, $userEtudiant,  [
                    'champDesactive' => false,
                ]);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $userEtudiant = $form->getData();
                     $entityManager = $this->getDoctrine()->getManager();
                     $entityManager->persist($userEtudiant); 
                     $entityManager->persist($userEtudiant);
                     $entityManager->flush();
                     $this->addFlash('success', 'Informations modifiées avec succès !');
                     return $this->render('etudiant/showEdit.html.twig', array('form' => $form->createView(),'templateTwigParent' => 'baseAdmin.html.twig'));       
               }
               else
               {
                    
                    return $this->render('etudiant/showEdit.html.twig', array('form' => $form->createView(),'templateTwigParent' => 'baseAdmin.html.twig')); 
            
                }
            }

    }

    /*
     * Permet de lister les comptes des étudiants qui n'ont pas encore été activés
     */
    public function listerComptesNonActifs(): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findBy(['statut' => 'AR']);     
        return $this->render('admin/listComptesNonActifs.html.twig', ['users' => $users]);
    }

    /*
     * Permet de valider un compte étudiant (passer le statut user de AR à AC)
     */
    public function validerCompte($email): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(['email' => $email]); 
        $user->setStatut('AC');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('success', 'Le compte de '. $user->getEtudiant()->getprenom() .' '. $user->getEtudiant()->getNom() .  ' a été validé avec le mail '.$user->getEmail());
        return $this->redirectToRoute('listerComptesNonActifs');
    }

    /*
     * Permet de supprimer un compte étudiant
     */
    public function supprimerCompte($email): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(['email' => $email]);
       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        //$entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('success', 'Le compte de '. $user->getEtudiant()->getprenom() .' '. $user->getEtudiant()->getNom() .  ' a été supprimé avec le mail '.$user->getEmail());
        return $this->redirectToRoute('listerComptesNonActifs');
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
