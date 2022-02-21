<?php

namespace App\Form;

use App\Entity\SemaineStage;
use App\Entity\TacheSemaine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\TacheSemaineType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SemaineStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
            $builder
            ->add('apprentissage', TextareaType::class, 
            array('label' => 'apprentissage',  
            'help' => 'Qu\'avez-vous appris cette semaine ?' 
            ))
            ->add('bilan', TextareaType::class, 
            array('label' => 'bilan',  
            'help' => 'Quels sont les points positifs et/ou négatifs  ?' 
            ))
            //->add('stage')
            ->add('enregistrer', SubmitType::class, array('label' => 'Enregister bilan semaine'))
        ;


        /* Supression car fait avec table + modal*/
        //$builder->add('tacheSemaines', TacheSemaineType::class);
        
        /*
        $builder->add('tacheSemaines', CollectionType::class, [
            'entry_type' => TacheSemaineType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
        ]);*/
        


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SemaineStage::class,
        ]);
    }
}
