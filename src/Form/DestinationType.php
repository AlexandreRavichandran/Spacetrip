<?php

namespace App\Form;

use App\Entity\Spacecraft;
use App\Entity\Destination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DestinationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la destination'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('distance', NumberType::class, [
                'label' => 'Distance par rapport à la Terre'
            ])
            ->add('spacecrafts', EntityType::class, [
                'class' => Spacecraft::class,
                'choice_label' => 'name',
                'label' => 'Vaisseau dispobinbles pour cette destination',
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Destination::class,
        ]);
    }
}
