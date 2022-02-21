<?php

namespace App\Form;

use App\Entity\TacheSemaine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TacheSemaineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('description')
            ->add('description', TextareaType::class, 
            array('label' => 'description',  
            'help' => 'décrivez une des tâches du jour' 
        ))
            ->add('domaineTache', EntityType::class, array('label'=> 'domaine','class' => 'App\Entity\DomaineTache','choice_label' => 'libelle'))
            //->add('domaineTache')
            ->add('jour', EntityType::class, array('class' => 'App\Entity\Jour','choice_label' => 'nom'))
            //->add('semaineStage')
            ->add('ajouter', SubmitType::class, array('label' => 'Valider tache'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TacheSemaine::class,
        ]);
    }
}
