<?php

namespace App\Form;

use App\Entity\RP;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RPType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libcourt', TextType::class, 
                array('label' => 'Libellé',  
                'help' => 'Intitulé court décrivant l\'action menée (développement, installation) et l\'objet sur lequel elle porte (service, application)' ,
                'disabled' => $options['champDesactive']
            ))
                
            ->add('besoin', TextareaType::class,[
                'disabled' => $options['champDesactive'],
                'help' => 'Dire ce que l\'entreprise veut changer à son activité et comment répondre à son besoin' ,
              ])
            ->add('dateDebut', DateType::class, array('input' => 'datetime',
            'widget' => 'single_text',
            'disabled' => $options['champDesactive'],
            'label' =>'date début',
            'placeholder' => 'jj/mm/aaaa'))

            ->add('dateFin', DateType::class, array('input' => 'datetime',
                                                          'widget' => 'single_text',
                                                          'disabled' => $options['champDesactive'],
                                                          'label' =>'date fin',
                                                          'placeholder' => 'jj/mm/aaaa'))
              ->add('moyen', TextareaType::class, [
                'disabled' => $options['champDesactive'],
                'help' => 'Logiciels et outils utilisés pour réaliser votre travail ' ,
                'label' =>'Moyens',
              ])
            ->add('environnement', TextareaType::class,[
                'disabled' => $options['champDesactive'],
                'help' => 'Ressources et documentations mises à votre disposition' ,
              ])
            
            //->add('dateModif')
            //->add('archivage')
            ->add('localisation', EntityType::class, array('class' => 'App\Entity\Localisation','choice_label' => 'libelle', 'disabled' => $options['champDesactive']))
            //->add('statut')
            ->add('source', EntityType::class, array('class' => 'App\Entity\Source','choice_label' => 'libelle', 'disabled' => $options['champDesactive']))
            ->add('cadre', EntityType::class, array('class' => 'App\Entity\Cadre','choice_label' => 'libelle', 'disabled' => $options['champDesactive']))
            ->add('niveauRP', EntityType::class, array('class' => 'App\Entity\NiveauRP','choice_label' => 'libelle', 'disabled' => $options['champDesactive']))
        ;
        $builder->add('cancel', SubmitType::class,[
            'disabled' => $options['champDesactive'],
            'label' => 'Annuler'
          ]);
        $builder->add('submit', SubmitType::class,[
            'disabled' => $options['champDesactive'],
            'label' => 'Enregistrer'
          ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RP::class,
            'champDesactive'=> false,
        ]);
    }
}
