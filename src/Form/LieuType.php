<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null, ['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Nom']])
            ->add('rue',null, ['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Rue']])
            ->add('latitude',null, ['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Latitude']])
            ->add('longitude',null, ['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Longitude']])
            ->add('ville',null, ['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Ville']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
