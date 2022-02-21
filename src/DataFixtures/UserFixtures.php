<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Enseignant;

class UserFixtures extends Fixture
{
    
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    
    public function load(ObjectManager $manager)
    {
        /*$user = new User();
        $user -> setEmail('zakina.annouche@ac-normandie.fr');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'mpZakina'
        ));
        $manager->persist($user);

        $user2 = new User();
        $user2 -> setEmail('serge.guerinet@ac-normandie.fr');
        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'mpSerge'
        ));
        $manager->persist($user2);
        $manager->flush(); 

        $user2 = new User();
        $user2 -> setEmail('admin@rostand.fr');
        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'mpAdmin'
        ));
        $manager->persist($user2);
        $manager->flush();*/


        /*$userAdmin = new User();
        $userAdmin -> setEmail('zakina.spammy@gmail.com');
        //email remplacé par admin directement en bdd
        $userAdmin->setPassword($this->passwordEncoder->encodePassword(
            $userAdmin,
            'mpAdmin'
        ));
        $roles[] = 'ROLE_ADMIN';
        $userAdmin ->setRoles($roles);
        $manager->persist($userAdmin);
        $manager->flush();
        */

        $userProf = new User();
        $userProf->setStatut('AC');
        $userProf -> setEmail('zakina.annouche@ac-normandie.fr');
        $userProf->setPassword($this->passwordEncoder->encodePassword(
            $userProf,
            'mpZakina'
        ));
        $roles[] = 'ROLE_ENSEIGNANT';
        $userProf ->setRoles($roles);

        $e = new Enseignant();
        $e->setNom('Annouche');
        $e->setPrenom('Zakina');
        $userProf->setEnseignant($e);


        $manager->persist($userProf);
        $manager->flush();
        

    }
}