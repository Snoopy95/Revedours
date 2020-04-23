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
                    'placeholder' => 'Complément'
                ]
            ])
            ->add('cp', IntegerType::class, [
                'attr' => [
                    'placeholder' => '75000'
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'Paris'
                ]
            ])
            ->add('phone', TelType::class, [
                'attr' => [
                    'pattern' => '[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{2}',
                    'size' => 15,
                    'placeholder' => '01.10.20.30.40'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Addresses::class,
        ]);
    }
}
