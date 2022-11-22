<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AjoutClientType extends AbstractType
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
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('photo', FileType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Photo de profil',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false
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
            'data_class' => Client::class,
        ]);
    }
}
