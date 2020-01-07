<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AssignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subjects',EntityType::class,[
                'class' => Subject::class,
                    'placeholder' => '',
                    'empty_data' => null,
                    'required' => false,
                    'expanded' => true,
                    'multiple' => true,
                    'by_reference' => false,
            ])
            ->add('assign', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-primary btn-block',
                )
            ))
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
