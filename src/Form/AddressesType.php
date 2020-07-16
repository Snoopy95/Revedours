<?php

namespace App\Form;

use App\Entity\Addresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AddressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'placeholder' => 'Adresse'
                ]
            ])
            ->add('address1', TextType::class, [
                'attr' => [
                    'placeholder' => 'Adresse',
                    'class' => 'mt-2',
                ],
                'required' => false
            ])
            ->add('cp', IntegerType::class, [
                'attr' => [
                    'placeholder' => '75000',
                    'pattern' => '[0-9]{5}'
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'Paris'
                ]
            ])
            ->add('phone', TelType::class, [
                'attr' => [
                    'pattern' => "^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$",
                    'size' => 15,
                    'placeholder' => '01.10.20.30.40',
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Addresses::class,
        ]);
    }
}
