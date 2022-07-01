<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => [
                'class' =>'form-control-user',
                'placeholder' =>'name'
            ],
            'label' => FALSE
        ])
        ->add('parent', TextType::class, [
            'attr' => [
                'class' =>'form-control',
                'placeholder' =>'parent'
            ],
            'label' => FALSE,
            'required' => FALSE
        ])
        // ->add('quantity', NumberType::class, [
        //     'attr' => [
        //         'class' =>'form-control',
        //         'placeholder' =>'quantity'
        //     ],
        //     'label' => FALSE,
        //     'required' => FALSE
        // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
