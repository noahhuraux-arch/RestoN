<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Serveur;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

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
                    return 'Table n°' . ($r->getTable() ? $r->getTable()->getId() : '?');
                },
                'placeholder' => 'Vente directe',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'query_builder' => function (EntityRepository $er) use ($restaurant) {
                    return $er->createQueryBuilder('p')
                        ->where('p.idRestau = :res')
                        ->setParameter('res', $restaurant);
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
