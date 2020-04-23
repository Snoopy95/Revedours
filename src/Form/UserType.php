<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Adresse Email'
                ]
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom ou Pseudo'
                ]
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'show-pwd',
                    'placeholder' => 'Votre mot de passe'
                ]
            ])
            ->add('confirm_password', PasswordType::class, [
                'attr' => [
                    'class' => 'showconf-pwd',
                    'placeholder' => 'Confirmez votre mot de passe'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
