<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Article;
use App\Entity\Commentary;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\UserLikeRepository;
use App\Repository\CommentaryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("profile/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/commentary", name="user_commentary")
     */
    public function userCommentary(CommentaryRepository $commentaryRepository, ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator)
    {
        $dataComment = $commentaryRepository->userComment();

        $comments = $paginator->paginate(

            $dataComment,
            $request->query->getInt('page', 1),
            10
        );

        $searchForm = $this->createFormBuilder(null)
            ->add('Choose', ChoiceType::class, [

                'choices' => [

                    'Défaut' => 1,

                    'Auteur' => [

                        'Croissant' => 2.1,
                        'Décroissant' => 2.2
                    ],

                    'Date' => [

                        'Croissant' => 3.1,
                        'Décroissant' => 3.2
                    ],

                    'Titre' => [

                        'A-Z' => 4.1,
                        'Z-A' => 4.2
                    ]
                ],
            ])
            ->add('query', TextType::class)
            ->add('recherche', SubmitType::class, [

                'attr' => [

                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm()
        ;

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            //dd($data = $searchForm->getData());

            $data = $searchForm->getData();

            if ($data['Choose'] == 2.1) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->ascendingAutor($data['query']),
                ]);
            }

            if ($data['Choose'] == 2.2) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->descendingAutor($data['query']),
                ]);
            }

            if ($data['Choose'] == 3.1) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->ascendingDate($data['query']),
                ]);
            }

            if ($data['Choose'] == 3.2) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->descendingDate($data['query']),
                ]);
            }

            if ($data['Choose'] == 4.1) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->ascendingTitle($data['query']),
                ]);
            }

            if ($data['Choose'] == 4.2) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->descendingTitle($data['query']),
                ]);
            }

            return $this->render('search/search.html.twig', [

                'filters' => $articleRepository->findSearch($data['query']),
            ]);
        }

        return $this->render('user/commentary.html.twig', [

            'comments' => $comments,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/share", name="user_share")
     */
    public function userShare(UserLikeRepository $userLikeRepository, ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator)
    {
        $dataLike = $userLikeRepository->userIdLike($this->getUser());

        $likes = $paginator->paginate(

            $dataLike,
            $request->query->getInt('page', 1),
            10
        );

        $searchForm = $this->createFormBuilder(null)
            ->add('Choose', ChoiceType::class, [

                'choices' => [

                    'Défaut' => 1,

                    'Auteur' => [

                        'Croissant' => 2.1,
                        'Décroissant' => 2.2
                    ],

                    'Date' => [

                        'Croissant' => 3.1,
                        'Décroissant' => 3.2
                    ],

                    'Titre' => [

                        'A-Z' => 4.1,
                        'Z-A' => 4.2
                    ]
                ],
            ])
            ->add('query', TextType::class)
            ->add('recherche', SubmitType::class, [

                'attr' => [

                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm()
        ;

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            //dd($data = $searchForm->getData());

            $data = $searchForm->getData();

            if ($data['Choose'] == 2.1) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->ascendingAutor($data['query']),
                ]);
            }

            if ($data['Choose'] == 2.2) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->descendingAutor($data['query']),
                ]);
            }

            if ($data['Choose'] == 3.1) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->ascendingDate($data['query']),
                ]);
            }

            if ($data['Choose'] == 3.2) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->descendingDate($data['query']),
                ]);
            }

            if ($data['Choose'] == 4.1) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->ascendingTitle($data['query']),
                ]);
            }

            if ($data['Choose'] == 4.2) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->descendingTitle($data['query']),
                ]);
            }

            return $this->render('search/search.html.twig', [

                'filters' => $articleRepository->findSearch($data['query']),
            ]);
        }

        return $this->render('user/share.html.twig', [

            'likes' => $likes,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/like", name="user_like")
     */
    public function userLike(UserLikeRepository $userLikeRepository, ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator)
    {
        //dd($user->getId());

        $dataLike = $userLikeRepository->userIdLike($this->getUser());

        $likes = $paginator->paginate(

            $dataLike,
            $request->query->getInt('page', 1),
            10
        );

        $searchForm = $this->createFormBuilder(null)
            ->add('Choose', ChoiceType::class, [

                'choices' => [

                    'Défaut' => 1,

                    'Auteur' => [

                        'Croissant' => 2.1,
                        'Décroissant' => 2.2
                    ],

                    'Date' => [

                        'Croissant' => 3.1,
                        'Décroissant' => 3.2
                    ],

                    'Titre' => [

                        'A-Z' => 4.1,
                        'Z-A' => 4.2
                    ]
                ],
            ])
            ->add('query', TextType::class)
            ->add('recherche', SubmitType::class, [

                'attr' => [

                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm()
        ;

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            //dd($data = $searchForm->getData());

            $data = $searchForm->getData();

            if ($data['Choose'] == 2.1) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->ascendingAutor($data['query']),
                ]);
            }

            if ($data['Choose'] == 2.2) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->descendingAutor($data['query']),
                ]);
            }

            if ($data['Choose'] == 3.1) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->ascendingDate($data['query']),
                ]);
            }

            if ($data['Choose'] == 3.2) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->descendingDate($data['query']),
                ]);
            }

            if ($data['Choose'] == 4.1) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->ascendingTitle($data['query']),
                ]);
            }

            if ($data['Choose'] == 4.2) {
                
                return $this->render('search/search.html.twig', [

                    'filters' => $articleRepository->descendingTitle($data['query']),
                ]);
            }

            return $this->render('search/search.html.twig', [

                'filters' => $articleRepository->findSearch($data['query']),
            ]);
        }

        return $this->render('user/like.html.twig', [

            'likes' => $likes,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/information", name="user_edit")
     * 
     */
    public function userEdit(Request $request, User $user)
    {
        $userEditForm = $this->createForm(UserUpdateType::class, $user);
        $userEditForm->handleRequest($request);

        if ($userEditForm->isSubmitted() && $userEditForm->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('user/information.html.twig', [

            'user' => $user,
            'form' => $userEditForm->createView()
        ]);
    }

    /**
     * @Route("/", name="user_index")
     */
    public function index(CommentaryRepository $commentaryRepository): Response
    {
        return $this->render('user/index.html.twig', [

            'commentaries' => $commentaryRepository->findBy([], ['date' => 'desc'], 5)
        ]);
    }
}
