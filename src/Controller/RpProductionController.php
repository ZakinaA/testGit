<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Production ;
use App\Entity\RP ;
use App\Form\ProductionType;

class RpProductionController extends AbstractController
{
    /*
     * Liste les productions d'une rp passée en paramètre
     */
    public function rpProductionList($idRp): Response
    {
        if ($idRp == 0)
        {
            $production = new Production();
            return $this->redirectToRoute('rpShowEditAdd',array('idRp'=> 0));
            //return new Response ('gérer ce cas ou pas de rp et on clique sur activites- renvoyer vers formulaire');
        }
        else
        //idRpActivite est différent de 0, on veut donc modifier une rpActiicite existante
        {
            $repository = $this->getDoctrine()->getRepository(RP::class);
            $rp = $repository->find($idRp);
            if ($this->getUser()->getEnseignant() !=null )
            {
                return $this->render('rp/listProductions.html.twig', array('rp'=> $rp, 'templateTwigParent' => 'baseEnseignant.html.twig'));
            }
            else
            {

                if ($rp->getEtudiant()->getid() != $this->getUser()->getEtudiant()->getId()  ){
                throw $this->createAccessDeniedException();
                }
                    return $this->render('rp/listProductions.html.twig', array('rp'=> $rp, 'templateTwigParent' => 'baseEtudiant.html.twig'));
            }
        }
    }

    /*
     * Permet d'éditer la production d'une rp
     */
    public function rpProductionEdit(Request $request, $idProduction): Response
    {

        //si idRpActivite=0, c'est une nouvelle acticité pour cette rp
        if ($idProduction == 0)
        {
            $prod = new Production();
            
        }
        else
        //idProduction est différent de 0, on veut donc modifier une production existante
        {
            
            $repository = $this->getDoctrine()->getRepository(Production::class);
            $prod = $repository->find($idProduction);

            if ($prod->getRp()->getEtudiant()->getid() != $this->getUser()->getEtudiant()->getId()  ){
                throw $this->createAccessDeniedException();
            }

            $form = $this->createForm(ProductionType::class, $prod);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $prod = $form->getData();  
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($prod);
                $entityManager->flush(); 
            
                // retourner la liste des productions de la rp 
                return $this->redirectToRoute('rpProductionList', ['idRp' => $prod->getRP()->getId()]);
            
            }

            return $this->render('rp/addEditProduction.html.twig', array('form' => $form->createView(), 'idRp' => $prod->getRP()->getId()));
        }
    }

    /*
     * Permet d'ajouter une production à une rp
     */
    public function rpProductionAdd(Request $request, $idRp): Response
    {
        $prod = new Production();
	    $form = $this->createForm(ProductionType::class, $prod);
	    $form->handleRequest($request);
 
	    if ($form->isSubmitted() && $form->isValid()) {
            
            $repository = $this->getDoctrine()->getRepository(RP::class);
            $rp = $repository->find($idRp);

            $prod = $form->getData();
            $prod->setRp($rp);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prod);
            $entityManager->flush();
            return $this->redirectToRoute('rpProductionList', ['idRp' => $idRp]);
            
	    }
	    else
        {
            return $this->render('rp/addEditProduction.html.twig', array('form' => $form->createView(), 'idRp' =>$idRp));
	    }
    }

    /*
     * Permet de supprimer une production dont l'id est passée en paramètre
     */
    public function rpProductionRemove($idProduction): Response
    {        
        $repository = $this->getDoctrine()->getRepository(Production::class);
        $production = $repository->find($idProduction);
        if ($production->getRp()->getEtudiant()->getid() != $this->getUser()->getEtudiant()->getId()  ){
            throw $this->createAccessDeniedException();
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($production);
        $entityManager->flush();
        return $this->redirectToRoute('rpProductionList', ['idRp' => $production->getRp()->getId()]);
            

    }





}
