<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Commentary;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaryType extends AbstractType
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;  
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = new User();

        $builder
            /* ->add('user', EntityType::class, [
                
                'class' => User::class,
                'query_builder' => function (EntityRepository $entityRepository) {
                    $user = new User();
                    return $entityRepository->createQueryBuilder('u')
                        ->where('u.id = :userId')
                        ->setParameter('userId', $user->getId())
                        ;
                },
                'choice' => $this->userRepository->findUserId($user->getId()),
                'choice_label' => 'pseudo',
                'label' => 'Pseudo',
            ])
            ->add('user', EntityType::class, [
                
                'class' => User::class,
                'query_builder' => function (EntityRepository $entityRepository) {
                    $user = new User();
                    return $entityRepository->createQueryBuilder('u')
                        ->where('u.id = :userId')
                        ->setParameter('userId', $user->getId());
                },
                'choice_label' => 'email',
                'label' => 'Email',
            ])
            ->add('article', EntityType::class, [
                
                'class' => Article::class,
                'choice_label' => 'id',
                'label' => 'Article',
            ]) */
            ->add('user', HiddenType::class)
            ->add('article', HiddenType::class)
            ->add('message', TextareaType::class, [

                'label' => 'Message'
            ])
            ->add('approve', HiddenType::class, [
                
                'data' => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentary::class,
        ]);
    }
}
