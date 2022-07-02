<?php

namespace App\Form;

use App\Entity\Typage;
use App\Entity\TypeCouteau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\CommandeTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TypageType extends AbstractType
{
    public function __construct(CommandeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nb_couteau',IntegerType::class,[
                'label' => 'Nombre de couteau',
                'attr' => [
                    'min' => '1', 
                    'max' => '50',
                    'class'=>'m-y'
                ],
            ])
            ->add('commande', HiddenType::class)
            ->add('typeCouteau',EntityType::class, [
                'label' => 'Type',
                'class' => TypeCouteau::class,
                'attr' => [
                    'class'=>'m-y'
                ]
            ])
        ;
        $builder
            ->get('commande')
            ->addModelTransformer($this->transformer);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Typage::class,
        ]);
    }
}
