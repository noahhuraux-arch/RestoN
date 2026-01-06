<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\EventListener\AddHeureFieldListener;
use App\Repository\TableRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    private TableRepository $tableRepository;

    public function __construct(TableRepository $tableRepository)
    {
        $this->tableRepository = $tableRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class)
            ->add('nbPers', IntegerType::class)
            ->add('client', ClientType::class)
        ;
        $builder->addEventSubscriber(new AddHeureFieldListener($this->tableRepository));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'restaurant' => null,
        ]);
    }
}
