<?php

namespace App\Form;

use App\Entity\Chaton;
use App\Entity\Proprietaire;
use phpDocumentor\Reflection\Types\True_;
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
            ->add('Chatons_id', EntityType::class, [
                "class" => Chaton::class,
                "choice_label" => "Nom",
                "multiple" => true,
                "expanded" => true,
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
