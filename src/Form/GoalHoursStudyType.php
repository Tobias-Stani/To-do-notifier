<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Materia;

class GoalHoursStudyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dailyGoal', IntegerType::class, [
                'label' => 'Meta diaria (en horas)',
                'required' => false,
                'attr' => ['min' => 0],
            ])
            ->add('weekGoal', IntegerType::class, [
                'label' => 'Meta semanal de estudio (en horas)',
                'required' => false,
                'attr' => ['min' => 0],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materia::class,
        ]);
    }
}