<?php

namespace App\Form;

use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libRestau', TextType::class, ['label' => 'Nom du restaurant'])
            ->add('adrRestau', TextType::class, ['label' => 'Adresse'])
            ->add('cpRestau', TextType::class, ['label' => 'Code Postal'])
            ->add('villeRestau', TextType::class, ['label' => 'Ville'])
            ->add('nbTable', IntegerType::class, ['label' => 'Nombre de tables', 'attr' => ['min' => 0]])
            ->add('nbEtoiles', IntegerType::class, ['label' => 'Nombre d\'étoiles', 'required' => false, 'attr' => ['min' => 0, 'max' => 3]])
            ->add('tel_restau', TelType::class, ['label' => 'Téléphone'])
            ->add('email_restau', EmailType::class, ['label' => 'Email'])
            ->add('logoFile', FileType::class, [
                'label' => 'Logo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(maxSize: '2M', mimeTypes: ['image/jpeg', 'image/png', 'image/webp'], mimeTypesMessage: 'Format invalide (JPG, PNG, WEBP)'),
                ],
            ])
            ->add('banniereFile', FileType::class, [
                'label' => 'Bannière',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(maxSize: '2M', mimeTypes: ['image/jpeg', 'image/png', 'image/webp'], mimeTypesMessage: 'Format invalide (JPG, PNG, WEBP)'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
