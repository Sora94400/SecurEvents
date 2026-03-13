<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $inputStyle = 'w-full bg-slate-800 border border-slate-600 rounded-xl p-3 text-white focus:border-amber-500 outline-none transition mt-1';

        $builder
            ->add('titre', TextType::class, [
                'attr' => ['class' => $inputStyle]
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => $inputStyle . ' h-32']
            ])
            ->add('dateDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => $inputStyle]
            ])
            ->add('lieu', TextType::class, [
                'attr' => ['class' => $inputStyle]
            ])
            ->add('capaciteMax', IntegerType::class, [
                'attr' => ['class' => $inputStyle]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une catégorie',
                'attr' => ['class' => $inputStyle],
                'required' => false,
            ])
            ->add('isPublished', CheckboxType::class, [
                'required' => false,
                'label' => 'Publier immédiatement',
                'attr' => ['class' => 'w-5 h-5 text-amber-500 bg-slate-800 border-slate-600 rounded focus:ring-amber-500']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
