<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Restaurant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $restaurant = $options['restaurant'] ?? null;

        $builder
            ->add('libProduit', TextType::class, ['label' => 'Nom du menu'])
            ->add('prixProduit', IntegerType::class, ['label' => 'Prix du menu', 'attr' => ['min' => 0]])
            ->add('visible', CheckboxType::class, ['label' => 'Visible', 'required' => false])
            ->add('descriptionProduit', TextType::class, ['label' => 'Description du menu'])
            ->add('idPlat', EntityType::class, [
                'class' => Plat::class,
                'choice_label' => 'libProduit',
                'label' => 'Plats inclus',
                'multiple' => true,
                'expanded' => false,
                'query_builder' => $restaurant ? function (EntityRepository $er) use ($restaurant) {
                    return $er->createQueryBuilder('p')
                        ->where('p.idRestau = :restaurant')
                        ->setParameter('restaurant', $restaurant)
                        ->orderBy('p.typePlat', 'ASC');
                } : null,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'restaurant' => null,
        ]);
    }
}
