<?php

namespace App\Form;

use App\Entity\Stage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomEntreprise', TextType::class, array('required' => true, 'label' => 'nom entreprise *','disabled' => $options['champDesactive']))
            ->add('siret', TextType::class, array('required' => true, 'label' => 'code siret *','help' => '14 caractères', 'disabled' => $options['champDesactive']))
            ->add('codeNaf', TextType::class, array('required' => true, 'label' => 'code naf/ape *','help' => '5 carac. ex 1254Z', 'disabled' => $options['champDesactive']))
            //->add('numRue',TextType::class, array('required' => false, 'label' => 'numéro de rue','disabled' => $options['champDesactive']))
            ->add('rue', TextType::class, array('required' => false, 'label' => 'rue *','disabled' => $options['champDesactive']))
            ->add('copos', TextType::class, array('required' => false, 'label' => 'code postal *','disabled' => $options['champDesactive']))
            ->add('ville', TextType::class, array('required' => true, 'label' => 'ville *','disabled' => $options['champDesactive']))
            ->add('nomTuteur', TextType::class, array('required' => true, 'label' => 'nom tuteur *','disabled' => $options['champDesactive']))
            ->add('mailTuteur', EmailType::class, array('required' => true, 'label' => 'mail tuteur *','disabled' => $options['champDesactive']))
            ->add('telTuteur', TextType::class, array('required' => true, 'label' => 'tel tuteur *','disabled' => $options['champDesactive']))
            ->add('nomDirecteur', TextType::class, array('required' => true, 'label' => 'nom tuteur *','disabled' => $options['champDesactive']))
            ->add('mailDirecteur', EmailType::class, array('required' => false, 'label' => 'mail directeur','disabled' => $options['champDesactive']))
            ->add('telDirecteur', TextType::class, array('required' => false, 'label' => 'tel directeur','disabled' => $options['champDesactive']))
            ->add('lieu', TextType::class, array('help'=>'préciser si différent de l\'adresse de l\'entreprise','required' => false, 'label' => 'lieu de stage','disabled' => $options['champDesactive']))
            ->add('sujet', TextType::class, array('required' => true, 'label' => 'sujet *','disabled' => $options['champDesactive']))
            ->add('service', TextType::class, array('required' => false, 'label' => 'service','disabled' => $options['champDesactive']))
            ->add('dateDebut', DateType::class, array('input' => 'datetime',
            'widget' => 'single_text',
            'disabled' => $options['champDesactive'],
            'label' =>'date de début *',
            'placeholder' => 'jj/mm/aaaa'))
            ->add('dateFin', DateType::class, array('input' => 'datetime',
            'widget' => 'single_text',
            'disabled' => $options['champDesactive'],
            'label' =>'date de fin *',
            'placeholder' => 'jj/mm/aaaa'))
            ->add('duree', IntegerType::class, array('required' => true, 'label' => 'durée *','help' => 'nombre de semaines', 'disabled' => $options['champDesactive']))
            ->add('horLun', TextType::class, array('required' => false, 'label' => 'Horaire Lundi','disabled' => $options['champDesactive']))
            ->add('horMar', TextType::class, array('required' => false, 'label' => 'Horaire Mardi','disabled' => $options['champDesactive']))
            ->add('horMer', TextType::class, array('required' => false, 'label' => 'Horaire Mercredi','disabled' => $options['champDesactive']))
            ->add('horJeu', TextType::class, array('required' => false, 'label' => 'Horaire Jeudi','disabled' => $options['champDesactive']))
            ->add('horVen', TextType::class, array('required' => false, 'label' => 'Horaire Vendredi','disabled' => $options['champDesactive']))
            ->add('horSam', TextType::class, array('required' => false, 'label' => 'Horaire Samedi','disabled' => $options['champDesactive']))

            ->add('cancel', SubmitType::class,[
                'disabled' => $options['champDesactive'],
                'label' => 'Annuler'
              ])
            ->add('submit', SubmitType::class,[
                'disabled' => $options['champDesactive'],
                'label' => 'Enregistrer'
              ]);
        
    
            //->add('etudiant')
            //->add('enseignant')
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
            'champDesactive'=> true,
        ]);
    }
}
