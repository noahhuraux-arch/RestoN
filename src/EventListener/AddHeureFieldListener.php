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

    public function onPreSetData(PreSetDataEvent $event): void
    {
        $form = $event->getForm();
        $choices = [];
        $factory = $form->getConfig()->getFormFactory();
        $builder = $factory->createNamedBuilder('heure', ChoiceType::class, null,
            ['choices' => $choices, 'auto_initialize' => false, 'placeholder' => 'Choisir une date et un nombre de Personne']);
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
            $joursFr = [
                'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
                'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche',
            ];
            $nomJour = $joursFr[$date->format('l')];
            $horaireDuJour = null;
            foreach ($restaurant->getHoraires() as $horaire) {
                if ($horaire->getJour() === $nomJour) {
                    $horaireDuJour = $horaire;
                    break;
                }
            }
            if ($horaireDuJour && !$horaireDuJour->isFerme()) {
                $creneaux = [[$horaireDuJour->getOuvertureMidi(), $horaireDuJour->getFermetureMidi()],
                    [$horaireDuJour->getOuvertureSoir(), $horaireDuJour->getFermetureSoir()],
                ];
                $interval = new \DateInterval('PT30M');
                foreach ($creneaux as $heure) {
                    if ($heure[0] && $heure[1]) {
                        $Demiheure = new \DatePeriod($heure[0], $interval, $heure[1]);

                        foreach ($Demiheure as $journeeHoraire) {
                            if ($this->tableRepository->findAvailableTables($restaurant, $date, $journeeHoraire, (int) $nbPers)) {
                                $formatHeure = $journeeHoraire->format('H:i');
                                $choices[$formatHeure] = $formatHeure;
                            }
                        }
                    }
                }
            }
        }
        $placeholder = null;
        if (empty($choices) && !empty($dateStr)) {
            $placeholder = 'Veuillez saisir le nombre de personnes';
        }
        if (empty($choices) && !empty($dateStr) && (int) $nbPers) {
            $placeholder = 'Plus de place disponible pour cette date';
        }
        $factory = $form->getConfig()->getFormFactory();
        $builder = $factory->createNamedBuilder('heure', ChoiceType::class, null, ['choices' => $choices, 'auto_initialize' => false, 'placeholder' => $placeholder]);
        $builder->addModelTransformer(new CallbackTransformer(
            function ($Date) {
                if ($Date instanceof \DateTime) {
                    return $Date->format('H:i');
                } else {
                    return '';
                }
            },
            function ($stringHeure) {
                return new \DateTime($stringHeure);
            }
        ));
        $form->add($builder->getForm());
    }
}
