<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class AjoutUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>50,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 50 caractères"])
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prenom',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>50,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 50 caractères"])
                ]
            ])
            ->add('contact', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Contact',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>50,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 50 caractères"])
                ]
            ])
            ->add('adresse', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>50,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 50 caractères"])
                ]
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min'=>2,
                            'max'=>180,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 180 caractères"])
                ]
            ])
            ->add('roles', ChoiceType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Client' => "ROLE_USER",
                    'Entreprise' => "ROLE_USER",
                    'Professionnel' => "ROLE_PROFESSIONNAL",
                    'Administrateur' => "ROLE_ADMIN"
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Confirmation du mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ]
        )
        ->add('Valider', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4'
            ]
        ]);

             // Data transformer
               $builder->get('roles')
               ->addModelTransformer(new CallbackTransformer(
                   function ($rolesArray) {
                        // transform the array to a string
                        return count($rolesArray)? $rolesArray[0]: null;
                   },
                   function ($rolesString) {
                        // transform the string back to an array
                        return [$rolesString];
                   }
           ));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
