<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Your Name',
                'attr' => ['placeholder' => 'Enter your name']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Your Email',
                'attr' => ['placeholder' => 'Enter your email']
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Your Message',
                'attr' => ['placeholder' => 'Enter your message']
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Send Message',
                'attr' => ['class' => 'btn-send']
            ])
            ->add('service', ChoiceType::class, [
                'choices'  => [
                    'accounting' => 'account@localhost.com',
                    'Support' => 'support@localhost.com',
                    'marketing' => 'marketing@localhost.com',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
