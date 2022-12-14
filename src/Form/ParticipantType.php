<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mail',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'mail']])
            /*->add('roles')*/
            ->add('password', PasswordType::class, array('label' => 'Password','attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Password']))
            ->add('nom',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Nom']])
            ->add('prenom',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Prenom']])
            ->add('telephone',null,['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Telephone']])
            /*->add('administrateur')*/
            /*->add('actif')*/
            ->add('campus',null, ['attr'=>['class' => 'glass-button uk-margin', 'placeholder' => 'Nom']])
            ->add('imageFile',VichImageType::class,['required' => false])
            /*->add('sortieParticipants')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
