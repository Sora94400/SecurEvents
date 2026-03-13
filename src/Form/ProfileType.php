<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $attr = ['class' => 'w-full px-4 py-2 rounded-lg bg-slate-700/50 border border-slate-600 text-slate-100'];

        $builder
            ->add('email', EmailType::class, ['label' => 'Email', 'attr' => $attr])
            ->add('prenom', TextType::class, ['label' => 'Prénom', 'attr' => $attr])
            ->add('nom', TextType::class, ['label' => 'Nom', 'attr' => $attr]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
