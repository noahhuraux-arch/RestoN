<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Reservation;
use App\Entity\Serveur;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $restaurant = $options['restaurant'];

        $builder
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'query_builder' => function (EntityRepository $er) use ($restaurant) {
                    return $er->createQueryBuilder('p')
                        ->where('p.restaurant = :res')
                        ->setParameter('res', $restaurant)
                        ->orderBy('p.libProduit', 'ASC');
                },
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'libProduit',
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Commande::class, 'restaurant' => null]);
        $resolver->setRequired('restaurant');
    }
}
