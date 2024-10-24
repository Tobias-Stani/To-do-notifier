<?php

namespace App\Form;

use App\Entity\Materia;
use App\Entity\Tarea;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        ])
        ->add('materia', EntityType::class, [
            'class' => Materia::class,
            'choice_label' => 'nombre', // Campo que se mostrará en el select
            'attr' => [
                'class' => 'form-control',
            ],
            'placeholder' => 'Selecciona una materia', // Opción por defecto
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tarea::class,
        ]);
    }
}
