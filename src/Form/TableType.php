<?php

namespace App\Form;

use App\Entity\Table;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class TableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', IntegerType::class, [
                'label' => 'Numéro de la table',
                'attr' => [
                    'placeholder' => 'Ex: 10',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un numéro']),
                    new Positive(['message' => 'Le numéro doit être supérieur à 0'])
                ]
            ])
            ->add('nbPlace', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => [
                    'placeholder' => 'Ex: 4',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le nombre de places']),
                    new Positive(['message' => 'Il doit y avoir au moins 1 place'])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Table::class,
        ]);
    }
}
