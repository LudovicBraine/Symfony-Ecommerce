<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 60
                ]),
                'label' => 'New password',
                'invalid_message' => "Passwords aren't similar",
                'attr' => [
                    'placeholder' => 'Your new password'
                ],
                'required' => true,
                'first_options' => [
                    'label' => 'New Password',
                    'attr' => [
                        'placeholder' => "Write your new password"
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirm your new password',
                    'attr' => [
                        'placeholder' => "Please confirm your new password"
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Update your password',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
