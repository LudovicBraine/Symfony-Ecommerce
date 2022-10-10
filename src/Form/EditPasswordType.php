<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true
            ])
            ->add('old_password', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Please select your current password'
                ],
                'label' => 'Current password',
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
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
                'label' => 'Save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
