<?php

namespace App\Form;

use App\Entity\Plat;
use App\Entity\TypePlat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libProduit', TextType::class, ['label' => 'Nom'])
            ->add('prixProduit', IntegerType::class, ['label' => 'Prix', 'attr' => ['min' => 0]])
            ->add('visible', CheckboxType::class, ['label' => 'Visible', 'required' => false])
            ->add('descriptionProduit', TextType::class, ['label' => 'Description', 'required' => false])
            ->add('typePlat', EntityType::class, [
                'class' => TypePlat::class,
                'placeholder' => 'Choisissez un type',
                'choice_label' => 'lib',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
