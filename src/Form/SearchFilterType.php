<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Campus;
use App\Entity\SearchFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus',
                'placeholder' => 'Choisir un campus',
                'required' => false,
                'choice_attr' => function () {
                    return ['class' => 'bg-palette-light-dark'];
                },
            ])
            ->add('search', SearchType::class, [
                'label' => 'Le nom de la sortie contient',
                'attr' => [
                    'placeholder' => '🔎︎ Recherche dans le nom',
                ],
                'required' => false,
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Entre',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Et',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('organizer', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur•trice',
                'required' => false,
            ])
            ->add('registered', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit•e',
                'required' => false,
            ])
            ->add('notRegistered', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit•e',
                'required' => false,
            ])
            ->add('finished', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'FILTRER',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchFilter::class,
        ]);
    }
}
