<?php

namespace App\Form;

use App\Entity\Carrera;
use App\Entity\Cuatrimestre;
use App\Entity\Materia;
use App\Entity\Tarea;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MateriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('cuatrimestre', EntityType::class, [
                'class' => Cuatrimestre::class,
                'choice_label' => function (Cuatrimestre $cuatrimestre) {
                    $carrera = $cuatrimestre->getCarrera(); 
                    
                    return $cuatrimestre->getNumero() . ' / ' . $cuatrimestre->getAnio() . 
                           ' (' . $carrera->getNombre() . ')'; 
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materia::class,
        ]);
    }
}
