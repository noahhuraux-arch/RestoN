<?php

namespace App\EventListener;

use App\Repository\TableRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvents;

class AddHeureFieldListener implements EventSubscriberInterface
{
    private TableRepository $tableRepository;

    public function __construct(TableRepository $tableRepository)
    {
        $this->tableRepository = $tableRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    /**
     * @throws \Exception
     */
    public function onPreSetData(PreSetDataEvent $event): void
    {
        $reservation = $event->getData();
        $form = $event->getForm();
        $restaurant = $form->getConfig()->getOption('restaurant');
        $choices = [];
        $date = $reservation?->getDate();
        $nbPers = $reservation?->getNbPers();
        if ($restaurant && $date && $nbPers) {
            $creneaux = ['12:00', '13:00', '14:00', '19:00', '20:00', '21:00', '22:00'];
            foreach ($creneaux as $horaire) {
                $heure = new \DateTime($horaire);
                if ($this->tableRepository->findAvailableTables($restaurant, $date, $heure, (int) $nbPers)) {
                    $choices[$horaire] = $horaire;
                }
            }
        }
        $factory = $form->getConfig()->getFormFactory();
        $builder = $factory->createNamedBuilder('heure', ChoiceType::class, null, ['choices' => $choices, 'auto_initialize' => false]);
        $builder->addModelTransformer(new CallbackTransformer(
            function ($Date) {
                return ($Date instanceof \DateTimeInterface) ? $Date->format('H:i') : '';
            },
            function ($stringHeure) {
                return new \DateTime($stringHeure);
            }
        ));
        $form->add($builder->getForm());
    }

    /**
     * @throws \Exception
     */
    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $reservation = $event->getData();
        $form = $event->getForm();
        $restaurant = $form->getConfig()->getOption('restaurant');

        if (!$reservation) {
            return;
        }
        $choices = [];
        $dateStr = $reservation['date'];
        $date = $dateStr ? new \DateTime($dateStr) : null;
        $nbPers = $reservation['nbPers'];
        if ($restaurant && $date && $nbPers) {
            $creneaux = ['12:00', '13:00', '14:00', '19:00', '20:00', '21:00', '22:00'];
            foreach ($creneaux as $horaire) {
                $heure = new \DateTime($horaire);
                if ($this->tableRepository->findAvailableTables($restaurant, $date, $heure, (int) $nbPers)) {
                    $choices[$horaire] = $horaire;
                }
            }
        }
        $factory = $form->getConfig()->getFormFactory();
        $builder = $factory->createNamedBuilder('heure', ChoiceType::class, null, ['choices' => $choices, 'auto_initialize' => false]);
        $builder->addModelTransformer(new CallbackTransformer(
            function ($Date) {
                return ($Date instanceof \DateTimeInterface) ? $Date->format('H:i') : '';
            },
            function ($stringHeure) {
                return new \DateTime($stringHeure);
            }
        ));
        $form->add($builder->getForm());
    }
}
