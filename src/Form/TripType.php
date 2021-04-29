<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\Spacecraft;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du voyage'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du voyage'
            ])
            ->add('destination', TextType::class, [
                'label' => 'Destination du voyage'
            ])
            ->add('departureAt', DateTimeType::class, [
                'label' => 'Date de départ du voyage'
            ])
            ->add('arrivalAt', DateTimeType::class, [
                'label' => 'Date d\'arrivée du voyage'
            ])
            ->add('availableSeatNumber', IntegerType::class, [
                'label' => 'Nombre de places disponibles'
            ])
            ->add('reserved', CheckboxType::class, [
                'label' => 'Voyage reservé'
            ])
            ->add('spacecraft', EntityType::class, [
                'class' => Spacecraft::class,
                'choice_label' => 'name',
                'label' => 'Vaisseau utilisé'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
