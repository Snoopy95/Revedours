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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UpdateProdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prod_name', TextType::class)
            ->add('prod_price', IntegerType::class)
            ->add('prod_descrip', TextType::class)
            ->add('prod_info', CKEditorType::class, [
                'config' => ['my_config'],
                'attr' => [
                    'row' => 5
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
