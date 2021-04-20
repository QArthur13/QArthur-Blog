<?php

namespace App\Form;

use DateTime;
use App\Entity\User;
use DateTimeInterface;
use App\Entity\Article;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
            ->add('category', TextType::class, [

                'label' => 'Catégorie',
            ])
            ->add('visibility', ChoiceType::class, [

                'choices' => [

                    'Visible' => 1,
                    'Invisible' => 0
                ],

                'label' => 'Visibilité',

                'expanded' => true,
                'multiple' => false
            ])
            ->add('image', FileType::class, [

                'label' => 'Selectionner une image',

                'mapped' => false,

                'required' => false
                //'data_class' => null
            ])
            //->add('date', HiddenType::class)
        ;

        /* $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

            $data = $event->getData();
            $form = $event->getForm();

            //dd($data['visibility']);

            if ($data['visibility'] == 0) {
                
                $form->add('date', DateTimeType::class, [

                    'label' => 'Date',
                ]);
            }
        }); */
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
