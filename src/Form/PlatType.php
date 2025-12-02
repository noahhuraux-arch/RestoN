<?php

namespace App\Form;

use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Entity\TypePlat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libProduit')
            ->add('prixProduit')
            ->add('visible')
            ->add('descriptionProduit')
            ->add('typePlat', EntityType::class, [
                'class' => TypePlat::class,
                'choice_label' => 'id',
            ])
            ->add('idRestau', EntityType::class, [
                'class' => Restaurant::class,
                'choice_label' => 'id',
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
