<?php

namespace App\Form;

use App\Entity\Allergene;
use App\Entity\Boisson;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoissonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libProduit', TextType::class, ['label' => 'Nom boisson'])
            ->add('prixProduit', NumberType::class, ['label' => 'Prix boisson', 'attr' => ['min' => 0]])
            ->add('visible', CheckboxType::class, ['label' => 'Visible ', 'required' => false, 'data' => true])
            ->add('descriptionProduit', TextType::class, ['label' => 'Description boisson', 'required' => false])
            ->add('alcoolise', CheckboxType::class, ['label' => 'Alcoolise ', 'required' => false])
            ->add('allergenes', EntityType::class, [
                'class' => Allergene::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Allergènes',
                'by_reference' => false,
            ])
            ->add('allergenes', EntityType::class, [
                'class' => Allergene::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Allergènes',
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boisson::class,
        ]);
    }
}
