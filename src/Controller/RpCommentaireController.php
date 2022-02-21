<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaire ;
use App\Entity\RP ;
use App\Entity\Statut ;
use App\Form\NotifierEnseignantType;
use App\Form\CommentaireEnseignantType;

class RpCommentaireController extends AbstractController
{
    
    /*
     * Liste les commentaires d'une rp passée en paramètre
     */
    public function rpCommentaireList($idRp): Response
    {
        
        $repository = $this->getDoctrine()->getRepository(RP::class);
        $rp = $repository->find($idRp);

        if ($rp->getEtudiant()->getid() != $this->getUser()->getEtudiant()->getId()  ){
            throw $this->createAccessDeniedException();
        }
        return $this->render('rp/listCommentairesRp.html.twig', array('rp'=> $rp));
        
    }
    
    /*
     * Permet à l'étudiant de soumettre sa rp à un enseignant
     */
    public function rpNotifier(Request $request, $idRp): Response
    {

        if ($idRp == 0)
        {return $this->redirectToRoute('rpShowEditAdd',array('idRp'=> 0));
            //return new Response ('gérer ce cas ou pas de rp et on clique sur activites- renvoyer vers formulaire');
        }
        else
        {


        $repository = $this->getDoctrine()->getRepository(RP::class);
        $rp = $repository->find($idRp);

        if ($rp->getEtudiant()->getid() != $this->getUser()->getEtudiant()->getId()  ){
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(NotifierEnseignantType::class, $rp);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $rp = $form->getData();

            $repository = $this->getDoctrine()->getRepository(Statut::class);
            $statutEnAttente = $repository->find(2);
            $rp->setStatut($statutEnAttente);
            $entityManager = $this->getDoctrine()->getManager();

            //$rp->setEnseignant($enseignant);
            $entityManager->persist($rp);
            $entityManager->flush();
            $this->addFlash('success', 'Réalisation soumise avec succès à '.$rp->getEnseignant()->getPrenom(). ' ' .$rp->getEnseignant()->getNom() );
            return $this->render('rp/listCommentaires.html.twig', ['rp' => $rp,'form' => $form->createView()]);
          
        }
        else
        {
            return $this->render('rp/listCommentaires.html.twig', array('rp' => $rp, 'form' => $form->createView()));
        }
    }
        
    }
    
    /*
     * Permet à l'enseignant d'ajouter un commentaire
     * On met le champ enseignant de la rp à null
     * On change le statut de la rp pour le mettre à commentée
     */
    public function addCommentaire(Request $request, $idRp): Response
    {
        $repository = $this->getDoctrine()->getRepository(RP::class);
        $rp = $repository->find($idRp);
        
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireEnseignantType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $rp->setEnseignant(null);
            $repository = $this->getDoctrine()->getRepository(Statut::class);
            $statutCommentée = $repository->find(3);
            $rp->setStatut($statutCommentée);
            
            $commentaire = $form->getData();
            $commentaire->setDateCommentaire(new \DateTime('now'));
            $commentaire->setEnseignant($this->getUser()->getEnseignant());
            $commentaire->setRp($rp);
          
            $entityManager = $this->getDoctrine()->getManager();

            //$rp->setEnseignant($enseignant);
            $entityManager->persist($commentaire);
            $entityManager->flush();
           
            $this->addFlash('success', 'Commentaire soumis avec succès à '.$rp->getEtudiant()->getPrenom(). ' ' .$rp->getEtudiant()->getNom() );
            return $this->render('rp/addCommentaire.html.twig', ['rp' => $rp,'form' => $form->createView()]);
          
        }
        else
        {
            return $this->render('rp/addCommentaire.html.twig', ['rp' => $rp,'form' => $form->createView()]);
          
        }
    }
    
    
    
    
    
    
    /**
     * @Route("/commentaire", name="commentaire")
     */
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }
}
