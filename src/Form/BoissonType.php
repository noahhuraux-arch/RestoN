<?php

namespace App\Form;

use App\Entity\Boisson;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoissonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libProduit', TextType::class, ['label' => 'Nom boisson'])
            ->add('prixProduit', IntegerType::class, ['label' => 'Prix boisson'])
            ->add('visible', BooleanType::class, ['label' => 'Visible ? (0 - 1)'])
            ->add('descriptionProduit', TextType::class, ['label' => 'Description boisson'])
            ->add('alcoolise', BooleanType::class, ['label' => 'Alcoolise ? (0 - 1)'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boisson::class,
        ]);
    }
}
