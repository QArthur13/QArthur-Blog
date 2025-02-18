<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Article;
use App\Entity\Commentary;
use App\Entity\Contact;
use App\Entity\Newsletter;
use App\Entity\UserLike;
use App\Entity\UserShare;
use App\Form\CommentaryType;
use App\Form\ContactType;
use App\Form\NewsletterType;
use App\Form\UserLikeType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentaryRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping\Id;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/numero/{id}", name="article")
     */
    public function article(Article $article, CommentaryRepository $commentaryRepository, Request $request)
    {
        $commentary = new Commentary();
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('EUROPE/Paris'));
        $commentaryForm = $this->createForm(CommentaryType::class, $commentary);

        $commentaryForm->handleRequest($request);

        if ($commentaryForm->isSubmitted() && $commentaryForm->isValid()) {
            
            $commentary
                ->setArticle($article)
                ->setUser($this->getUser())
                ->setDate($date)
            ;

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentary);
            $entityManager->flush();

            return $this->redirectToRoute('default');
        }

        return $this->render('article.html.twig', [

            'article' => $article,
            'commentary' => $commentaryRepository->eachCommentary($article->getId()),
            'commentaryForm' => $commentaryForm->createView()
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutSite(): Response
    {
        return $this->render('about.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
        $contact = new Contact();
        $contact_form = $this->createForm(ContactType::class, $contact);

        $contact_form->handleRequest($request);

        if ($contact_form->isSubmitted() && $contact_form->isValid()) {
            
            $entity_manager = $this->getDoctrine()->getManager();

            $entity_manager->persist($contact);

            $entity_manager->flush();

            $this->addFlash('success', 'Votre message à bien été pris en compte!');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact.html.twig', [

            'form' => $contact_form->createView()
        ]);
    }

    /**
     * @Route("/creation", name="create")
     */
    public function creation(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user_form = $this->createForm(UserType::class, $user);

        $user_form->handleRequest($request);

        if ($user_form->isSubmitted() && $user_form->isValid()) {

            $entity_manager = $this->getDoctrine()->getManager();

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $entity_manager->persist($user);

            $entity_manager->flush();

            $this->addFlash('success', 'Votre compte à bien été créer. :)');
            //return $this->render('user/home.html.twig', []);
            return $this->redirectToRoute('create');
        }
        
        return $this->render('create.html.twig', [

            'form' => $user_form->createView()
        ]);
    }

    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function FunctionName(Request $request)
    {
        $newsletter = new Newsletter();
        $newsletterForm = $this->createForm(NewsletterType::class, $newsletter);

        $newsletterForm->handleRequest($request);

        if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($newsletter);

            $entityManager->flush();
            
            $this->addFlash('success', 'Merci pour le newsletter!');

            return $this->redirectToRoute('newsletter');
        }

        return $this->render('newsletter.html.twig', [

            'form' => $newsletterForm->createView()
        ]);
    }

    /**
     * @Route("/partage/{id}", name="userShare")
     */
    public function userShare(Article $article)
    {
        $userShare = new UserShare();
        //dd($article);

        $userShare
            ->setArticle($article)
            ->setUser($this->getUser())
        ;

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userShare);
        $entityManager->flush();

        $this->addFlash('success', 'Merci d\'avoir partager cet article!');

        return $this->redirectToRoute('default');

        dd($userShare);
    }

    /**
     * @Route("/aimer/{id}", name="userLike")
     */
    public function userLike(Request $request, Article $article)
    {
        $userLike = new UserLike();
        //dd($article);

        $userLike
            ->setArticle($article)
            ->setUser($this->getUser())
        ;

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userLike);
        $entityManager->flush();

        $this->addFlash('success', 'Merci d\'avoir aimer cet article!');

        return $this->redirectToRoute('default');

        dd($userLike);
    }

    /**
     * @Route("/", name="default")
     */
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $dataArticle = $articleRepository->findBy([], ['date' => 'desc']);

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

        return $this->render('home.html.twig', [

            'articles' => $articles,
            'searchForm' => $searchForm->createView()

            ]);
    }
}
