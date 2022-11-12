<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name address (residence, company ...)',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Name the address'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Your firstname'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Lastname',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Your lastname'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Company',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Company name'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => true,
                'attr' => [
                    'placeholder' => 'address'
                ]
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Zipcode',
                'required' => true,
                'attr' => [
                    'placeholder' => 'zipcode'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => true,
                'attr' => [
                    'placeholder' => 'City'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Country'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter phone number'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Save",
                'attr' => [
                    'class' => "btn-block btn-info"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
