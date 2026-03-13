<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On utilise les mêmes classes Tailwind que pour les événements
        $inputStyle = 'w-full bg-slate-800 border border-slate-600 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition mt-1';

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la catégorie',
                'attr' => [
                    'class' => $inputStyle,
                    'placeholder' => 'Ex: Conférences, Ateliers, etc.'
                ],
                'label_attr' => [
                    'class' => 'text-slate-400 text-sm font-semibold'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}