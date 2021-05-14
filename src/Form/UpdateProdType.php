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

class UpdateProdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prod_name', TextType::class)
            ->add('prod_price', MoneyType::class, [
                'currency' => "EUR"
            ])
            ->add('prod_descrip', TextType::class)
            ->add('prod_info', CKEditorType::class, [
                'config' => ['my_config'],
                'attr' => [
                    'row' => 5
                ]
            ])
            ->add('prod_stock', ChoiceType::class, [
                'choices' => [
                    'Afficher' => true,
                    'Cacher' => false
                ],
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'input-group'
                ]
            ])
            ->add('categories', EntityType::class, [
                'attr' => [
                    'class' => 'form-select'
                ],
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
