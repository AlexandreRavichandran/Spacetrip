<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\Spacecraft;
use App\Entity\Destination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('price', NumberType::class, [
                'label' => 'Prix maximum',
                'required' => false,
            ])
            ->add('Destinations', EntityType::class, [
                'placeholder' => 'Selectionnez une destination',
                'label' => 'Destination',
                'class' => Destination::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('Spacecrafts', EntityType::class, [
                'placeholder' => 'Selectionnez un vaisseau',
                'class' => Spacecraft::class,
                'choice_label' => 'name',
                'label' => 'Vaisseaux',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
