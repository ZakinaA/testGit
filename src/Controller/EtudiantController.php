<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Etudiant;
use App\Entity\RP;
use App\Form\UserEtudiantType;


class EtudiantController extends AbstractController
{
    /*
     * Page accueil étudiant : renvoie la liste des 7 dernières rp et des stages
     */
    public function home()
    {
         return $this->render('etudiant/home.html.twig', [
                'etudiant' => $this->getUser()->getEtudiant(),
                 ]);
    }


    /**
     * Consultation /Edition d'un étudiant
     */
    public function showEdit(Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_ETUDIANT');
        $user = $this->getUser();
        
        $form = $this->createForm(UserEtudiantType::class, $user, ['champDesactive' => true,]);
        $form->handleRequest($request);  

        if(!$user){
            echo ("etudiant non trouvé");
            throw $this->createNotFoundException('Aucun user connecté !!!');
        }
        else
        {
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $user = $form->getData();
                $nom= $user->getEtudiant()->getNom();
                $user->getEtudiant()->setNom(strtoupper($nom));
                $entityManager = $this->getDoctrine()->getManager();
              
                //on renomme le fichier avec l'id etudiant et on l'upload sur le serveur dans le dossier configuré dans service.yaml     
                $fileDownload = $form['upload_file']->getData();
                if (file_exists($fileDownload))
                {
                    $fileName = $user->getEtudiant()->getId().'.'.$fileDownload->guessExtension();         
                    $fileDownload->move(
                         $this->getParameter('upload_directory'),
                         $fileName
                    );
                }

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Informations modifiées avec succès !');
                return $this->render('etudiant/showEdit.html.twig', array('form' => $form->createView(),  'templateTwigParent' => 'baseEtudiant.html.twig'));       
          }
            else
            {
               
                return $this->render('etudiant/showEdit.html.twig', array('form' => $form->createView(),  'templateTwigParent' => 'baseEtudiant.html.twig'));
   
            }

        }
    }


    /* 
     * Liste les rps non archivées d'un étudiant connecté
     */
    public function getLesRP(): Response
    { 
    
        $etudiant = $this->getUser()->getEtudiant();

        $repository = $this->getDoctrine()->getRepository(RP::class);
        $lesRps = $repository->findBy(
            ['etudiant' => $etudiant->getid(), 'archivage' => 0], array('dateModif'=>'desc'));
        return $this->render('etudiant/listRPs.html.twig', ['lesRps' => $lesRps]);
    }

    /*
     * Liste les rp archivées d'un étudiant connecté
     */
    public function getLesRPArchivees(): Response
    { 
    
        $etudiant = $this->getUser()->getEtudiant();
        //echo ('nom etudiant connecté '. $etudiant->getNom());

        $repository = $this->getDoctrine()->getRepository(RP::class);
        $lesRps = $repository->findBy(
            ['etudiant' => $etudiant->getid(), 'archivage' => 1], array('dateModif'=>'desc'));
        
        
        return $this->render('etudiant/listRPs.html.twig', [ 'lesRps' => $lesRps]);
    }


    /*
     * liste les rp par statut ???
     *
    public function getLesRPByStatut($idStatut): Response
    { 
    
        $etudiant = $this->getUser()->getEtudiant();
        //echo ('nom etudiant connecté '. $etudiant->getNom());

        $repository = $this->getDoctrine()->getRepository(RP::class);
        $lesRps = $repository->findBy(
            ['etudiant' => $etudiant->getid(), 'archivage' => 0, 'statut' => $idStatut], array('dateModif'=>'desc'));
        
        
        return $this->render('etudiant/listerRPs.html.twig', [ 'lesRps' => $lesRps]);
    }*/

}   