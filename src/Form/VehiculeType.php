<?php

namespace App\Form;

use App\Entity\Vehicule;
use App\Entity\VehiculeClasse;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('immatricule', TextType::class, array('label' => 'Immatricule'))
            ->add('marque', TextType::class, array('label' => 'Marque'))
            ->add('modele', TextType::class, array('label' => 'Modele'))
            ->add('num_serie', IntegerType::class, array('label' => 'N° série', 'attr' => ['min' => 0]))
            ->add('couleur', TextType::class, array('label' => 'Couleur'))
            ->add('nb_kilometre', IntegerType::class, array('label' => 'Nombre de Kilomètre','attr' => ['min' => 0]))
            ->add('date_achat', DateType::class, array('label' => 'Data achat', 'format' => 'yyyy-MM-dd',))
            ->add('prix_achat', IntegerType::class, array('label' => 'Prix', 'attr' => ['min' => 0]))
            ->add('Classe', )
            ->add('Classe', EntityType::class, [
                'class' => VehiculeClasse::class,
                'choice_label' => function(VehiculeClasse $Vehicule) {
                    return sprintf('(%d) %s', $Vehicule->getId(), $Vehicule->getName());
                }
            ]);
            //ChoiceType::class, array('label' => 'Type de véhicule','choices' => ["OK"]))
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
