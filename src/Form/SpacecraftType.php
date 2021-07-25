<?php

namespace App\Form;

use App\Entity\Spacecraft;
use App\Entity\Destination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SpacecraftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du vaisseau',
            ])
            ->add('brand', TextType::class, [
                'label' => 'Marque du vaisseau'
            ])
            ->add('possibleDestination', EntityType::class, [
                'label' => 'Destinations possibles avec ce vaisseau',
                'class' => Destination::class,
                'choice_label' => 'name',
                'multiple' => true

            ])
            ->add('numberOfSeat', IntegerType::class, [
                'label' => 'Nombres de sièges disponible'
            ])
            ->add('reservationPrice', NumberType::class, [
                'label' => 'Prix de reservation (€)'
            ])
            ->add('pricePerDistance', NumberType::class, [
                'label' => 'Prix kilometrique (€/km)'
            ])
            ->add('nationality', TextType::class, [
                'label' => 'Lieu de création du vaisseau'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du vaisseau'
            ])
            ->add('speed', NumberType::class, [
                'label' => 'Vitesse du vaisseau (km/h)'
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image du vaisseau (formats acceptés : JPG, PNG)',
                'required' => true,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => true,
                'imagine_pattern' => 'small_thumbnail',
                'asset_helper' => true,
                'attr' => [
                    "class" => "d-block text-center"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Spacecraft::class,
        ]);
    }
}
