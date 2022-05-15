<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Prestation;
use App\Repository\PrestationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    private $prestaRepo;

    public function __construct(PrestationRepository $prestaRepo)
    {
        $this->prestaRepo = $prestaRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $repo = $options['data'];
        $builder
            ->add('nb_couteau', NumberType::class)
            ->add('prestation', EntityType::class, [
                'class' => Prestation::class,
                'choices' => $this->prestaRepo->getCreneauLibre(),
            ])
            //->add('facture')
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
