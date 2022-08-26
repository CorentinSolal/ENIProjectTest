<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Nom de la sortie']])
            ->add('dateHeureDebut',DateTimeType::class,['widget'=>'single_text','attr'=>['class' => 'glass-button uk-margin','id' => 'cal']])
            ->add('duree',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Durée']])
            ->add('dateLimiteInscription',DateType::class,['widget'=>'single_text','attr'=>['class' => 'glass-button uk-margin']])
            ->add('nbInscriptionsMax',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Nombre d\'inscrits max']])
            ->add('infosSortie',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Infos suppémentaires']])
            ->add('campus',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Campus']])
            ->add('lieu',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Lieu']])
            ->add('etat',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Etat']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
