<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'expanded'  => true,
                'multiple'  => false, 
                    'choices' => [
                        'Admin' => User::admin,
                        'Professor' => User::professor,
                        'User' => User::user,
                    ],
                
            ])

            ->add('add', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-primary btn-block',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
