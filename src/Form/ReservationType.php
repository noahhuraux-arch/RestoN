<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class)
            ->add('heure', ChoiceType::class, [
                'choices' => [
                    'Midi' => [
                        '12:00' => new \DateTime('12:00'),
                        '13:00' => new \DateTime('13:00'),
                        '14:00' => new \DateTime('14:00'),
                    ],
                    'Soir' => [
                        '19:00' => new \DateTime('19:00'),
                        '20:00' => new \DateTime('20:00'),
                        '21:00' => new \DateTime('21:00'),
                        '22:00' => new \DateTime('22:00'),
                    ],
                ],
            ])
            ->add('nbPers', IntegerType::class)
            ->add('client', ClientType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
