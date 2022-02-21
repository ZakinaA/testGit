<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TacheSemaine ;
use App\Form\TacheSemaineType;

class TacheSemaineController extends AbstractController
{
    public function editTache(Request $request, $idTache)
    {
        $tache = $this->getDoctrine()
        ->getRepository(TacheSemaine::class)
        ->find($idTache);

        $idStage = $tache->getSemaineStage()->getStage()->getId();
        $numSemaine= $tache->getSemaineStage()->getNumSemaine();

        $form = $this->createForm(TacheSemaineType::class, $tache);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $tache = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tache);
            $entityManager->flush();

            return $this->redirectToRoute('semaineStageShowAdd', array( 'idStage' => $idStage, 'numSemaine' =>$numSemaine ));
        }
        else{  
            return $this->render('stage/editTache.html.twig', array('form' => $form->createView()));
        }
    }

    public function removeTache($idTache)
    {
        $tache = $this->getDoctrine()
        ->getRepository(TacheSemaine::class)
        ->find($idTache);

        $idStage = $tache->getSemaineStage()->getStage()->getId();
        $numSemaine= $tache->getSemaineStage()->getNumSemaine();

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($tache);
        $manager->flush();
        return $this->redirectToRoute('semaineStageShowAdd', array( 'idStage' => $idStage, 'numSemaine' =>$numSemaine ));
    }

}
