<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{

    public function __construct(private FormListenerFactory $factory)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('slug', TextType::class, [
                'required' => false
            ])
//            this code present if we want to show all the recipes in the category form
//            ->add('recipes', EntityType::class, [
//                'class' => Recipe::class,
//                'choice_label' => 'title',
//                'multiple' => true,
//                'by_reference' => false,
//                'expanded' => true
//            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-save'
                ]
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT,$this->factory->autoTime())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
