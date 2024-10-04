<?php

namespace App\Form;

use App\Entity\Tarea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titulo', null, [
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Ingresa el título'
            ]
        ])
        ->add('descripcion', null, [
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Ingresa la descripción'
            ]
        ])
        ->add('fecha', null, [
            'widget' => 'single_text',
            'attr' => [
                'class' => 'form-control',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tarea::class,
        ]);
    }
}
