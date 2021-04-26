<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\Spacecraft;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('destination')
            ->add('departureAt')
            ->add('arrivalAt')
            ->add('availableSeatNumber')
            ->add('reserved')
            ->add('spacecraft', EntityType::class, [
                'class' => Spacecraft::class,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
