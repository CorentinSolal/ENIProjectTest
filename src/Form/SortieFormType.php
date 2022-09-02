<?php

namespace App\Form;

use App\Entity\Sortie;
use App\Entity\Participant;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus',null, ['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Campus']])
            ->add('nom',null, ['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Nom']])
            ->add('date_heure_debut', DateType::class, ['label' => 'entre'])
            ->add('date_heure_debut', DateType::class, ['label' => 'et'])
            ->add('organisateur', CheckboxType::class, ['label' => 'Sorties dont je suis l\'organisateur/trice', 'required' => false, 'data' => true])
            ->add('isParticipant', ChoiceType::class, ['multiple' => true, 'expanded' => true, 'choices' => ['Sorties auxquelles je suis inscrit/e' => true, 'Sorties auxquelles je ne suis pas inscrit/e' => false,],])
            ->add('etat', CheckboxType::class, ['label'    => 'Sorties passées', 'required' => false,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
