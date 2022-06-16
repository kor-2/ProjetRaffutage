<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Prestation;
use App\Repository\PrestationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nb_couteau', NumberType::class)
        
        ->add('prestation', EntityType::class, [
            'class' => Prestation::class,
            'placeholder' => 'Choisir un créneau !',
            'query_builder' => function (PrestationRepository $prestaRepo) {
                return $prestaRepo->getCreneau(true,true);
            },
        ])
        ->add('Valider', SubmitType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}