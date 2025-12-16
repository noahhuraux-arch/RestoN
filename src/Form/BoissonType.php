<?php

namespace App\Form;

use App\Entity\Boisson;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boisson::class,
        ]);
    }
}
