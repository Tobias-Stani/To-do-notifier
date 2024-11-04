<?php

namespace App\Form;

use App\Entity\Carrera;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarreraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nombre', TypeTextType::class, [
            'label' => 'Nombre de la carrera',
            'attr' => [
                'class' => 'form-control', 
                'placeholder' => 'Ej: Licenciatura informatica', 
                'autocomplete' => 'off'
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carrera::class,
        ]);
    }
}
