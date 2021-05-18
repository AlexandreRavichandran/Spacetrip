<?php

namespace App\Form;

use App\Entity\Spacecraft;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SpacecraftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du vaisseau',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix du vaisseau'
            ])
            ->add('brand', TextType::class, [
                'label' => 'Marque du vaisseau'
            ])
            ->add('numberOfSeat', IntegerType::class, [
                'label' => 'Nombres de sièges disponible'
            ])
            ->add('nationality', TextType::class, [
                'label' => 'Lieu de création du vaisseau'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du vaisseau'
            ])
            ->add('speed', NumberType::class, [
                'label' => 'Vitesse du vaisseau (en km/h)'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Spacecraft::class,
        ]);
    }
}
