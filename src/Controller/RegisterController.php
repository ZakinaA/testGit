<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Etudiant;
use App\Entity\Niveau;
use App\Entity\Promotion;
use App\Entity\Specialite;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\MyMailer;
use App\Repository\UserRepository;


class RegisterController extends AbstractController
{
    private $mailer ;
    private $userRepository;
 
    public function __construct(MyMailer $myMailer, UserRepository $userRepository)
    {
        $this->mailer = $myMailer;
        $this->userRepository = $userRepository;
    }
  
    /**
     * Méthode permettant l'inscription d'un nouvel étudiant
     * On enregistre son email (dans User), nom, prénom, date de naissance
     * Par défaut, on renseigne 1ère année, sans spécialité, et promo en cours
     */
    public function addUserEtudiant(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
		$user = new user();
		$form = $this->createForm(RegistrationType::class, $user);
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

            $user = $form->getData();
            $user->setStatut('AR');
            $user->setCreatedAt(new \DateTime('now'));
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            $e = new etudiant();
            $e->setNom(strtoupper($form->get('nom')->getData()));
            $e->setPrenom($form->get('prenom')->getData());
            //$e->setDateNaiss($form->get('dateNaiss')->getData());

            $e->setSpecialite($form->get('specialite')->getData()); 

            $repository = $this->getDoctrine()->getRepository(Niveau::class);
            $niveau = $repository->find(1);
            $e->setNiveau($niveau);
            
            //affectation d'une promo par défaut
            // si la date en cours est de septembre en décembre, on prend l'année en cours, et on forme le nom de la promo avec annee en cours-annee en cours + 2
              // si la date en cours est de janvier à août, on prend l'année précédente, et on forme le nom de la promo avec annee en cours-annee en cours + 2
            $annee = date("Y");
            $mois = date("m");
            if($mois >=1 && $mois <9)
            {
                $annee = $annee - 1;
            }
            $libelPromo = strval($annee).'-'.strval($annee+2);
            $repository = $this->getDoctrine()->getRepository(Promotion::class);
		    $promo = $repository->findOneBy(['annee' => $libelPromo]);
            $e->setPromotion($promo);

            $repository = $this->getDoctrine()->getRepository(Niveau::class);
            $niveau = $repository->find(1);
            $e->setNiveau($niveau);

            $user->setEtudiant($e);
            //$user->setToken($this->generateToken());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //$this->mailer->sendEmail($user, $user->getToken());
            //$this->addFlash('Yep', 'check ta bal et confirme le mail');
            return $this->render('register/redirectionMailConfirm.html.twig', [
                'pEtudiant' =>  $user->getEtudiant(),
            ]);
	    }
        else{

            return $this->render('register/registration.html.twig', array(
                'form' => $form->createView(), ));
        }
    }

    /**
     * Appel de cette méthode lorsque le nouveau user clique sur le lien du mail
     * Il envoie le token généré à l'inscription
     * La méthode vérifie que le token enregistré en bdd à l'inscription est le même que celio envoyé par mail
     */
    public function confirmMail(String $token)
    {
        $user = $this->userRepository->findOneBy(["token"=>$token]);

        if ($user)
        {
            $user->setToken(null);
            $user->setStatut('AR');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('succès', 'inscription validée');
            return $this->redirectToRoute('app_login');
        }
        else
        {
            $this->addFlash('erreur', 'ce compte n\'existe pas');
            return $this->redirectToRoute('inscription');
        }
    }


    
    public function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

}
