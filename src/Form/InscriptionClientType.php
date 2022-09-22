<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class InscriptionClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'Email'))
            ->add('password', PasswordType::class, array('label' => 'Mot de passe'))
            ->add('nom', TextType::class, array('label' => 'Nom'))
            ->add('prenom', TextType::class, array('label' => 'Prénom'))
            ->add('dateNaissance', BirthdayType::class, array('label' => 'Date de naissance'))
            ->add('adresse', TextType::class, array('label' => 'Adresse'))
            ->add('telephone', IntegerType::class, array('label' => 'Téléphone', 'attr' => ['maxlength' => 10, 'min' => 0]))
            ->add('numeroPermis', TextType::class, array('label' => 'Numéro Permis', 'attr' => ['maxlength' => 15]));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
