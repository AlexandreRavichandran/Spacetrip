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
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
                'label' => 'Distance par rapport à la Terre (km)'
            ])
            ->add('spacecrafts', EntityType::class, [
                'class' => Spacecraft::class,
                'choice_label' => 'name',
                'label' => 'Vaisseau disponibles pour cette destination',
                'multiple' => true

            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de la destination',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true,
                //'imagine_pattern' => '...',
                'asset_helper' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Destination::class,
        ]);
    }
}
