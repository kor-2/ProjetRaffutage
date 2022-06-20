<?php

namespace App\Form;

use App\Entity\Typage;
use App\Entity\TypeCouteau;
use App\Form\DataTransformer\CommandeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class TypageType extends AbstractType
{
    public function __construct(CommandeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nb_couteau',NumberType::class,[
                'label' => 'Nombre de couteau',
                'attr' => ['min' => '1', 'max' => '50'],
            ])
            ->add('commande', HiddenType::class)
            ->add('typeCouteau',EntityType::class, [
                'label' => 'Type',
                'class' => TypeCouteau::class,
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