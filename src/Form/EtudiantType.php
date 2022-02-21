<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('nom', TextType::class,[
                'disabled' => $options['champDesactive']
            ])
            ->add('prenom', TextType::class,[
              'disabled' => $options['champDesactive']
            ])
            ->add('dateNaiss', DateType::class, array('input' => 'datetime',
                                                          'widget' => 'single_text',
                                                          'label' =>'date de naissance',
                                                          'placeholder' => 'jj/mm/aaaa'))
            
            ->add('promotion', EntityType::class, array('class' => 'App\Entity\Promotion','choice_label' => 'annee', 'disabled' => $options['champDesactive'],))
            ->add('niveau', EntityType::class, array('class' => 'App\Entity\Niveau','choice_label' => 'nomLong'))
            ->add('specialite', EntityType::class, array('class' => 'App\Entity\Specialite','choice_label' => 'nom',))
            /*
            ->add('mobile', TextType::class, array('required' => false))
            ->add('sexe', TextType::class, array('required' => false))
            ->add('numRue', TextType::class, array('required' => false))*/
            ->add('rue', TextType::class, 
                array(  'required' => true,
                        'help' => 'Saisir le numéro de la rue et la rue',
                        'label'=> 'rue *'      
            ))
            ->add('copos', TextType::class, array('required' => true, 'label' => 'code postal *',))
            ->add('ville', TextType::class, array('required' => true, 'label'=> 'ville *' ))
            //->add('cheminPhoto')
            //->add('statut', EntityType::class, array('class' => 'App\Entity\Promotion','choice_label' => 'nom'))     
            
           ;
  

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
            'champDesactive'=> true,
            
        ]);
    }
}
