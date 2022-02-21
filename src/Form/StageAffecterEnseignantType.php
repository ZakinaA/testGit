<?php

namespace App\Form;

use App\Entity\Stage;
use App\Entity\Enseignant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\EnseignantRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class StageAffecterEnseignantType extends AbstractType
{

    //Formulaire pour affecter un stage à un enseignant
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enseignant', EntityType::class, array('class' => 'App\Entity\Enseignant',
            'choice_label' => function (Enseignant $enseignant) {
                return $enseignant->getPrenom() . ' ' . $enseignant->getNom();
            },
            'query_builder' => function (EnseignantRepository $er) {
                return $er->createQueryBuilder('enseignant')
                ->AddOrderBy('enseignant.nom', 'asc')
                ->where('enseignant.matiere is NOT NULL');
            },
            ))
            
            ->add('selectionner', CheckboxType::class, [
                'label'    => 'affecter',
                'required' => false,
            ])
            ->add('affecter', SubmitType::class, array('label' => 'Affecter'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}