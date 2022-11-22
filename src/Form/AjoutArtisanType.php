<?php

namespace App\Form;


use App\Entity\Artisan;
use App\Entity\Utilisateur;
use App\Entity\CategorieMetier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AjoutArtisanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('idUtilisateur',EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nom',
                'choice_value' => 'id',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom Utilisateur',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('civilite', ChoiceType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Mr.' => "Mr. ",
                    'Mme/Mlle.' => "Mme/Mlle. "
                ],
                'label' => 'Civilité',
                'label_attr' => [
                    'class' => 'form-label mt-4 mt-4'
                ],
            ])
            ->add('photoProfile', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Photo de profil',
                'label_attr' => [
                    'class' => 'form-label mt-4 mt-4'
                ],
                'required' => false
                ])
            ->add('photoCouverture', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Photo de couverture',
                'label_attr' => [
                    'class' => 'form-label mt-4 mt-4'
                ],
                'required' => false
                ])
            ->add('idCategorieMetier',EntityType::class, [
                'class' => CategorieMetier::class,
                'choice_label' => 'nom_metier',
                'choice_value' => 'id',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Categorie Métier',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('statusJuridique', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Status Juridique',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>180,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 180 caractères"])
                ]
            ])
            ->add('siret', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'SIRET',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>180,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 180 caractères"])
                ]
            ])
            ->add('tva', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'TVA',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>180,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 180 caractères"])
                ]
            ])
            ->add('kbis', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'KBIS',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>180,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 180 caractères"])
                ]
            ])
            ->add('iban', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'IBAN',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>180,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 180 caractères"])
                ]
            ])
            ->add('bic', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'BIC',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2,
                            'max'=>180,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 180 caractères"])
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artisan::class,
        ]);
    }
}