<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'article',
                'label_attr' => ['class' => 'form-label mt-2'],
                'attr' => ['class' => 'form-control'],
                
            ])
            ->add('texte', TextareaType::class, [
                'label' => 'Texte de l\'article',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('publie', CheckboxType::class, [
                'label' => 'Publié ?',
                'attr' => ['class' => 'form-check-input mt-2'],
                'label_attr' => ['class' => 'form-label mt-1'],
                'required' => false
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de publication',
                'attr' => ['class' => 'form-label mt-2'],
                'widget' => 'single_text',
                'data' => new \DateTimeImmutable()  // Par défaut à la date actuelle
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
