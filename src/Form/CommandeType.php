<?php

namespace App\Form;

use App\Entity\Commande;
use App\Form\TypageType;
use App\Entity\Prestation;
use App\Repository\PrestationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('date_facturation')
            //->add('paye')
            //->add('lien_pdf')
            //->add('user')
            ->add('typages', CollectionType::class,[
                'entry_type' => TypageType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            //->add('prestation', HiddenType::class)
            
            /*
            ->add('prestation', EntityType::class, [
                'class' => Prestation::class,
                'placeholder' => 'Choisir un créneau !',
                'query_builder' => function (PrestationRepository $prestaRepo) {
                    return $prestaRepo->getCreneau(true,true);
                },
            ])*/
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
