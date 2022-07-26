<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateDeDebut', DateType::class, [
                'label' => 'Date de début',
                'years' => range(date('Y'), date('Y')+50),
            ])
            ->add('DateDeFin', DateType::class, [
                'label' => 'Date de fin',
                'years' => range(date('Y'), date('Y')+50),
            ])
            ->add('Sauvegarder', SubmitType::class, [
                'label' => 'Panier!',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
