<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $int=[];
        $coeff=[];
        for ($i=0; $i <= 20 ; $i++) { 
         $int[$i]=$i;
        }
        for ($c=1; $c <= 10; $c++) { 
            $coeff[$c]=$c;
        }
        $builder
            ->add('note',ChoiceType::class,[
                'choices'=>$int
            ])
            ->add('comment',TextareaType::class,[
                "label"=>"Commment","attr"=>[
                    'placeholder'=>"Enter your comment here"
                ]
            ])
            ->add('coeff',ChoiceType::class,[
                'choices'=>$coeff
            ])
            ->add('add', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-primary btn-block',
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
