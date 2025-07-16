<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UtilisateurType extends AbstractType
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
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prénom',
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
            ->add('contact' ,TextType::class, [
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
            ->add('adresse' ,TextType::class, [
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
            ->add('roles', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Roles',
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
                    'label' => 'Condirmation du mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
