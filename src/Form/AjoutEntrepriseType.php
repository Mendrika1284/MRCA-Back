<?php

namespace App\Form;


use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AjoutEntrepriseType extends AbstractType
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
                'label' => 'Nom Representant',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('nomEntreprise', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom Entreprise',
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
                            'max'=>50,
                            'minMessage'=>"Minimum 2 caractères",
                            'maxMessage'=>"Maximum 50 caractères"])
                ]
            ])
            ->add('tva', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Numero TVA',
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
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
