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

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,['attr'=>['class' => 'input', 'placeholder' => 'Nom']])
            ->add('prenom',null,['attr'=>['class' => 'input', 'placeholder'=>'Prenom']])
            ->add('telephone',null,['attr'=>['class' => 'input','placeholder'=>'Telephone']])
            ->add('mail',null,['attr'=>['class' => 'input','placeholder'=>'Mail']])
            ->add('campus',null,['attr'=>['class' => 'input','placeholder'=>'Campus']])
            ->add('plainPassword', RepeatedType::class,  ['type' =>PasswordType::class,
                'first_options'  => ['label'=>' ','attr' => ['class' => 'input', 'placeholder' => 'Password']],
                'second_options' => ['label'=>' ','attr' => ['class' => 'input', 'placeholder' => 'Repeat Password']],
                'mapped' => false,
                'constraints' => [new NotBlank(['message' => 'Please enter a password',]),
                    new Length(['min' => 6,'minMessage' => 'Your password should be at least {{ limit }} characters', 'max' => 4096,]),
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'attr'=>['class' =>'uk-checkbox'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('imageFile',VichImageType::class,['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
