<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RP ;
use App\Entity\Activite ;
use App\Entity\Competence ;
use App\Entity\Etudiant ;
use App\Entity\Statut ;
use App\Form\RPType;
use Symfony\Component\HttpFoundation\JsonResponse;

class RpController extends AbstractController
{
    /*
     * Liste les rp d'un étudiant
     */
    public function getLesRPByEtudiant($idEtudiant): Response
    {
 
        $repository = $this->getDoctrine()->getRepository(RP::class);
        $mesRps = $repository->findBy(
            ['etudiant' => $idEtudiant, 'archivage' => 0], array('dateModif'=>'desc'));
            
            return $this->render('rp/listerEtudiant.html.twig', [ 'pRP' => $mesRps]);  
        
    }

    /*
     * Liste les rp d'un étudiant et par statut
     */
    public function getLesRPByEtudiantByStatut($idEtudiant, $idStatut): Response
    {
        $etudiant = $this->getUser()->getEtudiant();
        $repository = $this->getDoctrine()->getRepository(RP::class);
        $mesRps = $repository->findBy(
            ['etudiant' => $idEtudiant, 'archivage' => 0, 'statut' => $idStatut], array('dateModif'=>'desc'));
            
            return $this->render('etudiant/listerRPs.html.twig', [ 'rps' => $mesRps,'etudiant' => $etudiant]);  
        
    }

    /*
     * Permet de consulter et d'éditer, d'ajouter une rp
     */
    public function showEditAdd(Request $request, $idRp): Response
    {
        $user = $this->getUser();  
        // paramètre par défaut de la route à 0. Si 0, on crée une nouvelle rp
        if ($idRp == 0)
        {
            $rp = new RP();         
        }
        else
        {
           // on est en modification
            $repository = $this->getDoctrine()->getRepository(RP::class);
            $rp = $repository->find($idRp);
            
            if ($rp->getEtudiant()->getid() != $user->getEtudiant()->getId()  ){
                throw $this->createAccessDeniedException();
            }
        }
        $etudiant = $this->getUser()->getEtudiant();

     
        $formRP = $this->createForm(RPType::class, $rp);
        $formRP->handleRequest($request);  

        if(!$etudiant){
            echo ("etudiant non trouvé");
            throw $this->createNotFoundException('Aucun etudiant connecté !!!');
        }
        else
        {
           
            if ($formRP->isSubmitted() && $formRP->isValid()) 
            {
                // valeurs par défaut d'une nouvelle rp : statut créé + date modif + non archivée(0)
                $statut = $this->getDoctrine()
                        ->getRepository(Statut::class)
                        ->find(1);
                $rp->setStatut($statut);
                $rp->setDateModif(new \DateTime('now'));
                $rp->setArchivage(0);

                $etudiant->addRp($rp);
                $etudiant = $formRP->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($etudiant);
                $entityManager->flush();
                $this->addFlash('success', 'Informations enregistrées avec succès !');                      
                return $this->render('rp/showEdit.html.twig', array('form' => $formRP->createView(), 'rp'=>$rp, 'templateTwigParent' => 'baseEtudiant.html.twig'));   
                //return $this->redirectToRoute('etudiantRps');    
            }
            else
            {
                return $this->render('rp/showEdit.html.twig', array('form' => $formRP->createView(), 'rp'=>$rp, 'templateTwigParent' => 'baseEtudiant.html.twig'));
   
            }

        }       
    }



    /*
     * Permet de consulter une rp pour l'enseignant
     */
    public function show(Request $request, $idRp): Response
    {
        $user = $this->getUser();  
        $repository = $this->getDoctrine()->getRepository(RP::class);
        $rp = $repository->find($idRp);
       
        $formRP = $this->createForm(RPType::class, $rp, ['champDesactive' => true,]);
        $formRP->handleRequest($request);  
         
        return $this->render('rp/showEdit.html.twig', array('form' => $formRP->createView(), 'rp'=>$rp, 'templateTwigParent' => 'baseEnseignant.html.twig'));            
    } 

    /*
     * Permet d'archiver une rp
     */
    public function archiver($idRp): Response
    {  
        $user = $this->getUser();  
        $repository = $this->getDoctrine()->getRepository(RP::class);
        $rp = $repository->find($idRp);
        if ($rp->getEtudiant()->getid() != $user->getEtudiant()->getId()  ){
            throw $this->createAccessDeniedException();
        }
        $rp->setArchivage(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rp);
        $entityManager->flush();
        $this->addFlash('success', 'La rp '. $rp->getLibCourt(). ' a été archivée');
        //return $this->redirectToRoute('some_other_route', array('entity' => $entity));
        return $this->redirectToRoute('etudiantRpsArchivees');    
        //return $this->render('etudiant/listRPs.html.twig', ['lesRps' => $user->getEtudiant()->getRPs()]); 
    } 


    /* 
     * Permet de desarchiver une rp
     */
    public function desarchiver($idRp): Response
    {  
        $user = $this->getUser();  
        $repository = $this->getDoctrine()->getRepository(RP::class);
        $rp = $repository->find($idRp);
        if ($rp->getEtudiant()->getid() != $user->getEtudiant()->getId()  ){
            throw $this->createAccessDeniedException();
        }
        $rp->setArchivage(0);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rp);
        $entityManager->flush();
        $this->addFlash('success', 'La rp '. $rp->getLibCourt(). ' a été désarchivée');
        //return $this->redirectToRoute('some_other_route', array('entity' => $entity));
        return $this->redirectToRoute('etudiantRps');    
        //return $this->render('etudiant/listRPs.html.twig', ['lesRps' => $user->getEtudiant()->getRPs()]); 
    }

     /* 
     * Permet de supprimer une rp
     */
    public function remove($idRp)
    {
        $user = $this->getUser();
        $rp = $this->getDoctrine()
        ->getRepository(RP::class)
        ->find($idRp);
    
        if ($rp->getEtudiant()->getid() != $user->getEtudiant()->getId()  ){
            throw $this->createAccessDeniedException();
        }

    
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($rp);
        $manager->flush();
        $this->addFlash('success', 'La rp '. $rp->getLibCourt(). ' a été désarchivée');
        return $this->redirectToRoute('etudiantRpsArchivees');    
    }


    /* 
     * # Liste les rp d'un étudiant
     */
    public function listParEtudiant($idEtudiant): Response
    { 


        $repository = $this->getDoctrine()->getRepository(Etudiant::class);
        $etudiant = $repository->find($idEtudiant);
        return $this->render('enseignant/listRpsParEtudiant.html.twig', ['etudiant' => $etudiant]);
        /*$lesRps = $repository->findBy(
            ['etudiant' => $idEtudiant], array('archivage'=>'asc','dateModif'=>'desc'));
        return $this->render('enseignant/listRpsParEtudiant.html.twig', ['lesRps' => $lesRps]);*/
    }


    /*
     * Permet de valider une rp
     */
    public function valider($idRp): Response
    {  
        $user = $this->getUser();  
        $repository = $this->getDoctrine()->getRepository(RP::class);
        $rp = $repository->find($idRp);
        /*if ($rp->getEtudiant()->getid() != $user->getEtudiant()->getId()  ){
            throw $this->createAccessDeniedException();
        }*/

        //récupération du statut validé
        $repository = $this->getDoctrine()->getRepository(Statut::class);
        $statut = $repository->find(4);

        $rp->setStatut($statut);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rp);
        $entityManager->flush();
        $this->addFlash('success', 'La rp '. $rp->getLibCourt(). ' a été validée');
        //return $this->redirectToRoute('some_other_route', array('entity' => $entity));
        return $this->redirectToRoute('enseignantHome');    
        //return $this->render('etudiant/listRPs.html.twig', ['lesRps' => $user->getEtudiant()->getRPs()]); 
    } 


}
