<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $attr = ['class' => 'w-full px-4 py-2 rounded-lg bg-slate-700/50 border border-slate-600 text-slate-100'];

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => array_merge($attr, ['placeholder' => 'vous@exemple.com']),
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => array_merge($attr, ['placeholder' => 'Jean']),
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => array_merge($attr, ['placeholder' => 'Dupont']),
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe', 'attr' => $attr],
                'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => $attr],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un mot de passe'),
                    new Length(min: 6, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
