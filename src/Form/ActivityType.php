<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\City;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('startDatetime', null, [
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie :'
            ])

            ->add('registerLimitDatetime', null, [
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription :'

            ])

            ->add('maxParticipants', null, [
                'label' => 'Nombre de places :'
            ])

            ->add('duration', null, [
                'widget' => 'single_text',
                'label' => 'Durée :'
            ])

            ->add('description', null, [
                'label' => 'Description et infos :'
            ])
            ->add('city', EntityType::class, [
                'mapped' => false,
                'class' => City::class,
                'choice_label' => 'name',
                'label' => 'Ville :',
                'placeholder' => 'Choisir une ville',
            ])

            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
                'label' => 'Lieu :',
                'placeholder' => 'Choisir un lieu',
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('publish', SubmitType::class, ['label' => 'Publier la sortie'])

        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
