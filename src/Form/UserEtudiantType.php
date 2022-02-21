<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Form\EtudiantType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserEtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', EmailType::class,[
            'disabled' => $options['champDesactive']
        ]); 
        /*->add('password', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'help' => 'Le mot de passe doit contenir au moins 8 caractères, au moins 1 chiffre et 1 caractère spécial',
            'label' => 'Mot de passe',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un mot de passe valide',
                ]),
                new Length([
                    'min' => 2,
                    'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ]);*/

        $builder->add('etudiant', EtudiantType::class,[
            'champDesactive' =>  $options['champDesactive'],
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


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'champDesactive'=>true,
        ]);
    }
}
