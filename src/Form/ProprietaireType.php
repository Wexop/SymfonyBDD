<?php

namespace App\Form;

use App\Entity\Chaton;
use App\Entity\Proprietaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProprietaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('Chaton', EntityType::class, [
                "class" => Chaton::class, //choix de la classe lié
                "choice_label" => "Nom", // choix de ce qui sera affiché comme texte
                "mapped" => false,
                "multiple" => true,
                "expanded" => false
            ])
            ->add("OK", SubmitType::class, ["label" => "Ajouter"]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proprietaire::class,
        ]);
    }
}
