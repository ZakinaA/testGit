<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserEnseignantType;
use App\Entity\Promotion;
use App\Entity\Specialite;
use App\Entity\Rp;
use App\Entity\Niveau ;
use App\Entity\Etudiant ;

class EnseignantController extends AbstractController
{
    /**
     * @Route("/enseignant", name="enseignant")
     */
    public function index(): Response
    {
        return $this->render('enseignant/index.html.twig', [
            'controller_name' => 'EnseignantController',
        ]);
    }

    /**
     * page d'accueil enseignant
     */
    public function home(): Response
    {
        return $this->render('enseignant/home.html.twig');
    }

    /**
    * consultation et édition d'un enseignant
    */
    public function showEdit(Request $request): Response
    {
        $userEnseignant =$this->getUser();
       
     
        $form = $this->createForm(UserEnseignantType::class, $userEnseignant, ['champDesactive' => true,]);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) 
        {
                $entityManager = $this->getDoctrine()->getManager();

                //on renomme le fichier avec l'id enseignant et on l'upload sur le serveur dans le dossier configuré dans service.yaml     
                $fileDownload = $form['upload_file']->getData();
                if (file_exists($fileDownload))
                {
                    $fileName = $userEnseignant->getEnseignant()->getId().'.'.$fileDownload->guessExtension();         
                    $fileDownload->move(
                        $this->getParameter('upload_directory_enseignant'),
                        $fileName
                    );
                }
                $entityManager->persist($userEnseignant);
                $entityManager->flush();
                $this->addFlash('success', 'Informations modifiées avec succès !');
        }
        return $this->render('enseignant/registerEnseignant.html.twig', array(
                    'form' => $form->createView(),  'templateTwigParent' => 'baseEnseignant.html.twig'));   
    
    }

     /*
      * Liste les étudiants des promos non archivées par spécialité, par niveau
      * La variable $source permet de savoir sur quel lien on a cliqué déclencher cette fonction
      * si $source=menu, on vient du menu de gauche et on affiche la liste de toutes les rp des etudiants de la spé et du niveau
      * si $source=tb, on affiche le tableau de bord (home enseignant) et on affiche les étudiant avec la liste des etudiants, le nb de rp, le nb de stage
     */
    public function listRpsEtudiantsParSpecialiteEtNiveau($idSpecialite, $idNiveau, $source): Response
    {
        $repository = $this->getDoctrine()->getRepository(Specialite::class);
        $specialite =  $repository->find($idSpecialite);

        $repository = $this->getDoctrine()->getRepository(Niveau::class);
        $niveau =  $repository->find($idNiveau);

        $repository = $this->getDoctrine()->getRepository(Etudiant::class);
        //$etudiants = $repository->listParSpecialiteParNiveau($specialite,$niveau);
        $etudiants = $repository->findBy(
            ['specialite' => $specialite, 'niveau' => $niveau], array('nom'=>'asc'));

        if ($source == 'tb'){
            return $this->render('enseignant/tableauBordParSpecialiteEtNiveau.html.twig', [ 'etudiants' => $etudiants]);  
        }
        if ($source == 'menu'){
            return $this->render('rp/listParSpecialiteEtNiveau.html.twig', [ 'etudiants' => $etudiants]);  
        }

      
        
    }

    
    /*
     * Liste toutes les RP  archivées
     * classées par date desc
     */
    public function listAllRpsArchivees(): Response
    {   
        $repository = $this->getDoctrine()->getRepository(RP::class);
        $lesRps = $repository->findBy(
            ['archivage' => 1], array('dateModif'=>'desc'));
       
        return $this->render('enseignant/listRpsArchivees.html.twig', [ 'lesRps' => $lesRps]); 
    }


}
