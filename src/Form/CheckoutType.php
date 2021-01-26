<?php

namespace App\Form;

use App\Entity\Checkout;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom du Titulaire'
                ]
            ])
            ->add('ncb', TextType::class, [
                'attr' => [
                    'pattern' => "[0-9]{16}",
                    'size' => 16,
                    'placeholder' => '1234123412341234',
                ]
            ])
            ->add('date', DateTimeType::class, [
            'attr' => [
                'placeholder' => '06/20'
            ],
            'widget' => 'single_text',
            'format' => 'MM/yy'
            ])
            ->add('cvv', NumberType::class, [
                'attr' => [
                    'pattern' => '[0-9]{3}',
                    'size' => 3,
                    'placeholder' => '123'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Checkout::class,
        ]);
    }
}
