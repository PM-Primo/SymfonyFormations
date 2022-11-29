<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\ModuleFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModuleFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_module' , TextType::class, ["attr" => ["class" => "form-control"]])
            ->add('categorie', EntityType::class, ['class' => Categorie::class, 'choice_label' => 'nom_categorie',"attr" => ["class" => "form-control"]])
        
            ->add('submit', SubmitType::class, ["attr" => ["class" => "btn btn-success"]])
        ;

        

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModuleFormation::class,
        ]);
    }
}
