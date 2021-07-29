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

            ->add('availableSeatNumber', NumberType::class)
            ->add('price', NumberType::class, [
                'label' => 'Prix maximum'
            ])
            ->add('Destination', EntityType::class, [
                'label' => 'Destination',
                'class' => Destination::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('spacecrafts', EntityType::class, [
                'class' => Spacecraft::class,
                'choice_label' => 'name',
                'label' => 'Vaisseaux',
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
