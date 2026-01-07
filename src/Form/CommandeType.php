<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Reservation;
use App\Entity\Serveur;
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
            ->add('serveur', EntityType::class, [
                'class' => Serveur::class,
                'choices' => $restaurant->getServeurs(),
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select']
            ])
            ->add('reservation', EntityType::class, [
                'class' => Reservation::class,
                'choices' => $restaurant->getReservations(),
                'choice_label' => function (Reservation $r) {
                    return 'Table n°' . ($r->getTable() ? $r->getTable()->getId() : '?') . ' (' . $r->getHeure()->format('H:i') . ')';
                },
                'placeholder' => 'Sans réservation / Vente directe',
                'mapped' => false, // On gère manuellement dans le controller
                'required' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'choices' => array_merge(
                    $restaurant->getPlats()->toArray(),
                    $restaurant->getBoissons()->toArray(),
                    $restaurant->getMenus()->toArray()
                ),
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
