<?php

namespace App\Form;

use App\Entity\Stagiaire;
use App\Entity\SessionFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule_session', TextType::class, ["attr" => ["class" => "form-control"]])
            ->add('date_debut', DateType::class, ['widget' => 'single_text', "attr" => ["class" => "form-control"]])
            ->add('date_fin', DateType::class, ['widget' => 'single_text', "attr" => ["class" => "form-control"]])
            ->add('nb_places', IntegerType::class, ["attr" => ["class" => "form-control"]])

            ->add('submit', SubmitType::class, ["attr" => ["class" => "btn btn-success"]])

        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SessionFormation::class,
        ]);
    }
}
