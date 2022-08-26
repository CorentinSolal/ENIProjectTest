<?php

namespace App\Form;

use App\Entity\Sortie;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Nom de la sortie']])
            ->add('dateHeureDebut',null,['attr'=>['class' => 'glass-button uk-margin']])
            ->add('duree',null,['attr'=>['class' => 'glass-button uk-margin']])
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Nombre d\'inscrits max']])
            ->add('infosSortie',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Infos suppémentaires']])
            ->add('campus')
            ->add('lieu')
            ->add('etat')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
