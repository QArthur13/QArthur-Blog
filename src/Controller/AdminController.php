<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Commentary;
use App\Form\CommentaryType;
use App\Form\CommentaryUpdateType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/commentary/{id}/suppression", name="commentary_delete")
     */
    public function deleteComment(Commentary $commentary)
    {
        //dd($commentary);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commentary);
        $entityManager->flush();

        return $this->redirectToRoute("admin_articles");
    }

    /**
     * @Route("/commentary/{id}/edit", name="commentary_edit")
     */
    public function editcommenatary(Commentary $commentary, Request $request)
    {
        $commentaryEditForm = $this->createForm(CommentaryUpdateType::class, $commentary);
        $commentaryEditForm->handleRequest($request);

        //dd($commentary);

        if ($commentaryEditForm->isSubmitted() && $commentaryEditForm->isValid()) {

            //dd($commentaryEditForm->getData());
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/commentaryEdit.html.twig', [

            'commentary' => $commentary,
            'commentaryForm' => $commentaryEditForm->createView()
        ]);
    }

    /**
     * @Route("/commentary/{id}/approuve", name="commentary_not_approve")
     */
    public function commenataryNotApprovation(Commentary $commentary, Request $request)
    {
        //dd($commentary);

        $commentary
            ->setApprove(false)
        ;

        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Le commentaire à bien été "non" approuvé!');

        return $this->redirectToRoute("admin_commentary");

        //dd($commentary);
    }

    /**
     * @Route("/commentary/{id}/approuve", name="commentary_approve")
     */
    public function commenataryApprovation(Commentary $commentary, Request $request)
    {
        //dd($commentary);

        $commentary
            ->setApprove(true)
        ;

        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Le commentaire à bien été approuvé!');

        return $this->redirectToRoute("admin_commentary");

        //dd($commentary);
    }

    /**
     * @Route("/article/{id}/suppression", name="article_delete")
     */
    public function deleteArticle(Article $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute("admin_articles");
    }

    /**
     * @Route("/article/{id}/edit", name="article_edit")
     */
    public function editArticle(Article $article, Request $request)
    {
        $articleEditForm = $this->createForm(ArticleType::class, $article);
        $articleEditForm->handleRequest($request);

        //dd($article);

        if ($articleEditForm->isSubmitted() && $articleEditForm->isValid()) {

            //dd($articleEditForm->getData());
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/edit_article.html.twig', [

            'article' => $article,
            'articleForm' => $articleEditForm->createView()
        ]);
    }

    /**
     * @Route("/creation", name="article_create")
     */
    public function addArticle(Request $request, SluggerInterface $slugger)
    {
        $article = new Article();
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('EUROPE/Paris'));
        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            $imageFile = $articleForm->get('image')->getData();

            if ($imageFile) {
                
                $originalImagename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                //dd($originalImagename);
                $safeImagename = $slugger->slug($originalImagename);
                //dd($safeImagename);
                $newImagename = 'image/'.$safeImagename.'-'.uniqid().'.'.$imageFile->guessExtension();
                //dd($newImagename);

                try {
                    $imageFile->move(
                        $this->getParameter('brochures_directory'),
                        $newImagename
                    );
                } catch (FileException $e) {
                    
                }
            }

            $article
                ->setDate($date)
                ->setImage($newImagename)
                ->setUser($this->getUser())
            ;

            //dd($article);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/articleCreate.html.twig', [

            'articleForm' => $articleForm->createView()
        ]);
    }

    /**
     * @Route("/commentaries", name="admin_commentary")
     */
    public function commentaryManagement(ArticleRepository $articleRepository, CommentaryRepository $commentaryRepository, Request $request, PaginatorInterface $paginator)
    {
        $dataCommentary = $commentaryRepository->findAll();

        $comments = $paginator->paginate(

            $dataCommentary,
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

        return $this->render('admin/commentary.html.twig', [

            'comments' => $comments,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/articles", name="admin_articles")
     */
    public function allArticles(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator)
    {
        $dataArticle = $articleRepository->findAll();

        $articles = $paginator->paginate(

            $dataArticle,
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

        return $this->render('admin/articles.html.twig', [

            'articles' => $articles,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/", name="admin_index")
     */
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $dataArticle = $articleRepository->findBy([], ['date' => 'desc'], 5);

        $articles = $paginator->paginate(

            $dataArticle,
            $request->query->getInt('page', 1),
            10
        );

        $searchForm = $this->createFormBuilder(null)
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
            
            $data = $searchForm->getData();

            return $this->render('admin/search.html.twig', [

                'search' => $articleRepository->findSearch($data['query'])
            ]);
        }

        return $this->render('admin/index.html.twig', [
            'articles' => $articles,
            'searchForm' => $searchForm->createView()
        ]);
    }
}
