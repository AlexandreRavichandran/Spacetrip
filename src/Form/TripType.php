<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\Spacecraft;
use App\Entity\Destination;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du voyage'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du voyage',
            ])
            ->add('departureAt', DateTimeType::class, [
                'label' => 'Date de départ du voyage',
                'attr' => [
                    'class' => 'd-flex justify-content-center',
                ]
            ])
            ->add('destination', EntityType::class, [
                'label' => 'Destination',
                'class' => Destination::class,
                'choice_label' => 'name'
            ])
            ->add('arrivalAt', DateTimeType::class, [
                'label' => 'Date d\'arrivée du voyage',
                'attr' => [
                    'class' => 'd-flex justify-content-center',
                ]
            ])
            ->add('availableSeatNumber', IntegerType::class, [
                'label' => 'Nombre de places disponibles'
            ])
            ->add('reserved', CheckboxType::class, [
                'label' => 'Voyage reservé',
                'required' => false
            ]);
        //https://www.youtube.com/watch?v=Uw9nrrccUpI&list=PLlxQJeQRaKDRs9WlWQiXNqWU0blyaZBzo&index=45

        $formModifier = function (FormInterface $form, Destination $destination = null) {

            if ($destination ===  null) {
                $spacecrafts = [];
            } else {
                $spacecrafts = $destination->getSpacecrafts();
            }

            $form->add('spacecraft', EntityType::class, [
                'class' => Spacecraft::class,
                'choice_label' => 'name',
                'label' => 'Vaisseau utilisé',
                'choices' => $spacecrafts,
                'placeholder' => 'Choisissez votre vaisseau',
            ]);
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifier) {

            $data = $event->getData();

            $formModifier($event->getForm(), $data->getDestination());
        });

        $builder->get('destination')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formModifier) {
            $destination = $event->getForm()->getData();
            $formModifier($event->getForm()->getParent(), $destination);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
