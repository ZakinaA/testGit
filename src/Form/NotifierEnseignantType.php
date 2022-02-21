<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\EnseignantRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class NotifierEnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
        ->add('enseignant', EntityType::class, array('class' => 'App\Entity\Enseignant',
          
                                                    'choice_label' => function ($enseignant) {
                                                        return $enseignant->getPrenom() . ' ' . $enseignant->getNom();},
                                                        
                                                     'placeholder' => 'Choisissez un enseignant',
                                                     'label' => false,
                                                     
                                                     'query_builder' => function (EnseignantRepository $er) {
                                                        return $er->createQueryBuilder('enseignant')
                                                        ->AddOrderBy('enseignant.nom', 'asc')
                                                        ->where('enseignant.matiere IN (:numero)')
                                                        ->setParameter('numero','1');
                                                        },))
        
        //->add('enregistrer', SubmitType::class, array('label' => 'Notifier'))

    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
