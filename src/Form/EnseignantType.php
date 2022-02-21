<?php

namespace App\Form;

use App\Entity\Enseignant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Niveau;
use App\Entity\Matiere;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder  
        ->add('nom',TextType::class,[
            'disabled' => $options['champDesactive']
            ])
            ->add('prenom',TextType::class,[
                'disabled' => $options['champDesactive'],
                'label' => 'Prénom'
            ])
            ->add('niveau', EntityType::class, array('class' => 'App\Entity\Niveau','choice_label' => 'nomLong',  'disabled' => $options['champDesactive'],))
            //->add('statut')
            ->add('matiere', EntityType::class, array('class' => 'App\Entity\Matiere','choice_label' => 'libelle',  'disabled' => $options['champDesactive'],))
           
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enseignant::class,
            'champDesactive'=>false,
        ]);
    }
}
