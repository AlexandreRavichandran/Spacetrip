<?php

namespace App\Form;

use App\Entity\Spacecraft;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpacecraftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('possibleDestination')
            ->add('brand')
            ->add('numberOfSeat')
            ->add('nationality')
            ->add('description')
            ->add('speed');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Spacecraft::class,
        ]);
    }
}
