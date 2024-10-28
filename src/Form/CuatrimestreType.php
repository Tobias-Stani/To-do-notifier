<?php

namespace App\Form;

use App\Entity\Carrera;
use App\Entity\Cuatrimestre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuatrimestreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', IntegerType::class, [
                'label' => 'Número del Cuatrimestre',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('anio', IntegerType::class, [
                'label' => 'Año',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('carrera', EntityType::class, [
                'class' => Carrera::class,
                'choice_label' => 'nombre',
                'label' => 'Carrera',
                'attr' => ['class' => 'form-select'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cuatrimestre::class,
        ]);
    }
}
