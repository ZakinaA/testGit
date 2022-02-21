<?php

// src/Security/RPVoter.php
namespace App\Security;

use App\Entity\Etudiant;
use App\Entity\RP;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RPVoter extends Voter
{
    // these strings are just invented: you can use anything
    const SHOWEDIT = 'showEdit';

    protected function supports(string $attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        //if (!in_array($attribute, [self::VIEW, self::EDIT])) {
        if (!in_array($attribute, [self::SHOWEDIT])) {
            return false;
        }

        // only vote on `Rp` objects
        if (!$subject instanceof Rp) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
      
        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a rp object, thanks to `supports()`
        /** @var Rp $rp */
        $rp = $subject;

        switch ($attribute) {
            case self::SHOWEDIT:
                return $this->canShowEdit($rp, $user);
            //case self::EDIT:
                //return $this->canEdit($rp, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canShowEdit(Rp $rp, User $user): bool
    {
        echo('id etudianr rp' . $rp->getEtudiant()->getid() );
        echo('id etuidnat user' .$user->getEtudiant()->getId() );
        return $rp->getEtudiant()->getid() === $user->getEtudiant()->getId();
        //return $user->getEtudiant()->getId() === $etudiant->getId();
    }

    /*private function canEdit(Etudiant $etudiant, User $user): bool
    {
        return $user->getEtudiant()->getId() === $etudiant->getId();
    }*/
}