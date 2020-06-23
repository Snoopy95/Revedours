<?php

namespace App\Form;

use App\Entity\Checkout;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
            ->add('ncb', IntegerType::class, [
                'attr' => [
                    'pattern' => '[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}',
                    'size' => 16,
                    'placeholder' => '000 0000 0000 0000 0000'
                ]
            ])
            ->add('date', IntegerType::class, [
            'attr' => [
                'pattern' => '[0-12]{1}/[20-99]{1}',
                'size' => 4,
                'placeholder' => '00/00'
                ]
            ])
            ->add('cvv', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Checkout::class,
        ]);
    }
}
