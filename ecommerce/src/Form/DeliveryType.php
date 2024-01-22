<?php

namespace App\Form;

use App\Entity\Adresses;
use App\Entity\Transporteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresses', EntityType::class, [
                'class' => Adresses::class,
                'choice_label' => function ($adresse) {
                    return $adresse->getStreet() . ', ' . $adresse->getCP() . ', ' . $adresse->getCity();
                },
            ])
            ->add('transporteur', EntityType::class, [
                'class' => Transporteur::class,
                'choice_label' => 'name', // Remplacez 'name' par le champ que vous souhaitez afficher
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider']);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => [],
        ]);
    }
}