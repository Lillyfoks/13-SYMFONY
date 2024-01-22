<?php

namespace App\Form;

use App\Entity\Adresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class AdressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('street', TextType::class, [ 
            'label' => 'Rue',
        ])
        ->add('CP', IntegerType::class, [
            'label' => 'Code Postal',
        ])
        ->add('City', TextType::class, [
            'label' => 'Ville',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresses::class,
        ]);
    }
}
