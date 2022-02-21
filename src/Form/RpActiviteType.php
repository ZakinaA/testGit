<?php

namespace App\Form;

use App\Entity\RPActivite;
use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RpActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('activite', EntityType::class, array(
            'placeholder' => 'Choisissez votre activité',
            'class' => 'App\Entity\Activite',
            'choice_label' => function (Activite $activite) {
                return $activite->getCode() . ' - ' . $activite->getLibelle();
            },
        ))
        ->add('commentaire', TextareaType::class, array(
            'help' =>'Décrivez ce que vous avez réalisé en rapport avec l\'activité sélectionnée',
            'label'=> 'Description du travail réalisé')
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RPActivite::class,
        ]);
    }
}
