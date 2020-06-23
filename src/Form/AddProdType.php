<?php

namespace App\Form;

use App\Entity\Products;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class AddProdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prod_name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('prod_price', MoneyType::class, [
                'currency' => 'EUR',
                'attr' => [
                    'placeholder' => 'Prix TTC'
                ]
            ])
            ->add('prod_descrip', TextType::class, [
                'attr' => [
                    'placeholder' => 'Info'
                ]
            ])
            ->add('prod_info', CKEditorType::class, [
                'config' => ['my_config'],
                'attr' => [
                    'rows' => 5
                ]
            ])
            ->add('prod_stock', ChoiceType::class, [
                'choices' => [
                    'Dispo' => true,
                    'Non Dispo' => false
                ],
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'input-group-text'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'cate_name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
