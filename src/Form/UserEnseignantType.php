<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Form\EnseignantType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserEnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', EmailType::class,[
          'disabled' => $options['champDesactive']
      ]); 
   
        $builder
        ->add('upload_file', FileType::class, [
          'label' => 'Photo',
          'mapped' => false, 
          'required' => false,
          'disabled' => false,
          'help' =>' Téléchargez une photo ou une image qui vous plait. Au format png uniquement.',
          'constraints' => [
            new File([ 
              'mimeTypes' => [ 
                'text/x-comma-separated-values', 
                'text/comma-separated-values', 
                //'image/gif', 
                //'image/jpeg', 
                'image/png'
                //'image/tiff'
              ],
              'mimeTypesMessage' => "Le format du fichier n'est pas une image au format png",
            ])
          ],
        ]);

        $builder->add('enseignant', EnseignantType::class,[
          'champDesactive' =>  $options['champDesactive'],
          
          ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'champDesactive'=>false,
        ]);
    }
}
