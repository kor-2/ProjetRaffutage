<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'inputReg'
                ]
            ])
            ->add('prenom', TextType::class,[
                'attr' => [
                    'placeholder' => 'Prenom',
                    'class' => 'inputReg'
                ]
            ]
            )
            ->add('telephone', TextType::class, [
                'attr' => [
                    'placeholder' => 'Téléphone',
                    'class' => 'inputReg'
                ]
            ])
            ->add('entreprise', TextType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entretreprise (facultatif)',
                    'class' => 'inputReg'
                ]
            ])
            ->add('email', EmailType::class,[
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'inputReg'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mot de passe doivent être similaire !',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => [
                    'label'=> false,
                    'attr' =>[
                        
                        'placeholder' => 'Mot de passe',
                        'class' => 'inputReg'
                    ]
            ],
                'second_options' => [
                    'label'=> false,
                    'attr' =>[
                        'placeholder' => 'Confirmer mot de passe',
                        'class' => 'inputReg'
                    ]
                ],
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Mot de passe vide',
                        ]
                    ),/*
                    new Regex(
                        [
                            'pattern' => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                            'message' => 'Doit contenir: majuscule, minuscule, chiffre et un caratère spécial',
                        ]
                    ),*/
                ],
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
