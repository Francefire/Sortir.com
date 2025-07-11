<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\City;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('startDatetime', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie :'
            ])

            ->add('registerLimitDatetime', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription :'

            ])

            ->add('maxParticipants', IntegerType::class, [
                'label' => 'Nombre de places :'
            ])

            ->add('duration', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Durée :'
            ])

            ->add('description', TextareaType::class, [
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
            ->add('image', FileType::class, [
                'label' => 'Image de l\'activité',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '3000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('publish', SubmitType::class, ['label' => 'Publier la sortie']);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
