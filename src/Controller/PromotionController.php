<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Specialite ;
use App\Entity\Niveau ;
use App\Entity\Etudiant ;

class PromotionController extends AbstractController
{
    /*
     * liste les promotions en cours
     */
    public function list(): Response
    {    
        
        return $this->render('promotion/listPromos.html.twig');
    }

    
}
