<?php

namespace App\Factory;

use App\Entity\Horaire;
// Importation ajoutée
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Horaire>
 */
final class HoraireFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Horaire::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return function ($input = []) {
            $attributes = is_array($input) ? $input : [];

            $faker = self::faker();

            if (!isset($attributes['restaurant'])) {
                $attributes['restaurant'] = RestaurantFactory::random();
            }

            if (!isset($attributes['jour'])) {
                $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                $attributes['jour'] = $faker->randomElement($jours);
            }

            $estFerme = $faker->boolean(15);

            $ouvertureMidi = null;
            $fermetureMidi = null;
            $ouvertureSoir = null;
            $fermetureSoir = null;

            $timesMidiOpen = ['11:30:00', '12:00:00', '12:30:00'];
            $timesMidiClose = ['13:30:00', '14:00:00', '14:30:00'];
            $timesSoirOpen = ['18:00:00', '18:30:00', '19:00:00', '19:30:00'];
            $timesSoirClose = ['21:30:00', '22:00:00', '22:30:00', '23:00:00'];

            if (!$estFerme) {
                if ($faker->boolean(80)) {
                    $timeString = $faker->randomElement($timesMidiOpen);
                    $ouvertureMidi = \DateTime::createFromFormat('H:i:s', $timeString);

                    $timeString = $faker->randomElement($timesMidiClose);
                    $fermetureMidi = \DateTime::createFromFormat('H:i:s', $timeString);
                }

                if ($faker->boolean(90)) {
                    $timeString = $faker->randomElement($timesSoirOpen);
                    $ouvertureSoir = \DateTime::createFromFormat('H:i:s', $timeString);

                    $timeString = $faker->randomElement($timesSoirClose);
                    $fermetureSoir = \DateTime::createFromFormat('H:i:s', $timeString);
                }

                if (!$ouvertureMidi && !$ouvertureSoir) {
                    $estFerme = true;
                }
            }

            return [
                'jour' => $attributes['jour'],
                'ouvertureMidi' => $ouvertureMidi,
                'fermetureMidi' => $fermetureMidi,
                'ouvertureSoir' => $ouvertureSoir,
                'fermetureSoir' => $fermetureSoir,
                'ferme' => $estFerme,
                'restaurant' => $attributes['restaurant'],
            ];
        };
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
        ;
    }
}
