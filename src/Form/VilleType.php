<?php

namespace App\Form;

use App\Entity\Ville;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Nom']])
            ->add('codePostal', TextType::class, [
        'required' => false,
                'attr' => ['class' => 'glass-button uk-margin', 'placeholder' => 'Code postal']
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
