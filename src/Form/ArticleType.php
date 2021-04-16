<?php

namespace App\Form;

use DateTime;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [

                'label' => 'Titre',
            ])
            ->add('contents', TextareaType::class, [

                'label' => 'Contenu',
            ])
            ->add('underTitle', TextType::class, [

                'label' => 'Sous-titre',
            ])
            ->add('user', HiddenType::class, [

                'label' => 'Auteur',
                'attr' => ['id' => 'app.user.id']
            ])
            ->add('category', TextType::class, [

                'label' => 'Catégorie',
            ])
            ->add('visibility', ChoiceType::class, [

                'choices' => [

                    'Visible' => 1,
                    'Invisible' => 0
                ],

                'preferred_choices' => [1],

                'label' => 'Visibilité',

                'expanded' => true,
                'multiple' => false
            ])
            ->add('image', FileType::class, [

                'label' => 'Selectionner une image',
            ])
            ->add('add', SubmitType::class, [

                'label' => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
