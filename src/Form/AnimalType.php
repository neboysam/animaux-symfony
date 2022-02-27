<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Famille;
use App\Entity\Personne;
use App\Entity\Continent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('poids')
            ->add('dangereux')
            ->add('famille', EntityType::class, [
                'class' => Famille::class,
                'choice_label' => 'libelle'
            ])
            ->add('continents', EntityType::class, [
                'class' => Continent::class,
                'choice_label' => 'libelle',
                /* 'choice_value' => function(Continent $continents) {
                    return $continents->getLibelle();
                }, */
                'multiple' => true,
                'expanded' => true,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
