<?php

namespace App\Form;

use App\Entity\Carrera;
use App\Entity\Cuatrimestre;
use App\Entity\Materia;
use App\Entity\Tarea;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MateriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre de la Materia',
                'attr' => [
                    'class' => 'form-control', // Clase de Bootstrap para el estilo
                    'placeholder' => 'Escriba el nombre de la materia', // Placeholder descriptivo
                    'autocomplete' => 'off',
                ],
            ])
            ->add('cuatrimestre', EntityType::class, [
                'class' => Cuatrimestre::class,
                'choice_label' => function (Cuatrimestre $cuatrimestre) {
                    $carrera = $cuatrimestre->getCarrera(); 
                    return $cuatrimestre->getNumero() . ' / ' . $cuatrimestre->getAnio() . 
                           ' (' . $carrera->getNombre() . ')'; 
                },
                'placeholder' => 'Seleccione el cuatrimestre', // Opción vacía para mejorar la usabilidad
                'label' => 'Cuatrimestre',
                'attr' => [
                    'class' => 'form-select', // Bootstrap para selects
                ],
                'help' => 'Seleccione el cuatrimestre correspondiente a la materia.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materia::class,
        ]);
    }
}
