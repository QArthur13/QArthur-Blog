<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Article;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentaryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    /* public function search(Request $request, ArticleRepository $articleRepository)
    {
        $searchForm = $this->createFormBuilder(null)
            ->add('query', TextType::class)
            ->add('recherche', SubmitType::class, [

                'attr' => [

                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm()
        ;

        $query = $request->query->get('query');

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            
            return $this->render('search.html.twig', [

                'serach' => $articleRepository->findSearch($query)
            ]);
        }

        return $this->render('home.html.twig', [

            'searchForm' => $searchForm->createView()
        ]);
    } */

    /**
     * @Route("/numero{id}", name="article")
     */
    public function article(Article $article, CommentaryRepository $commentaryRepository)
    {
        return $this->render('article.html.twig', [

            'article' => $article,
            'commentary' => $commentaryRepository->findAll()
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
     * @Route("/", name="default")
     */
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        /* $q = $request->query->get('q');
        $search = $articleRepository->findSearch($q); */

        $searchForm = $this->createFormBuilder(null)
            ->add('query', TextType::class)
            ->add('recherche', SubmitType::class, [

                'attr' => [

                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm()
        ;

        /* $query = $request->query->get('query');
        $query = $request->request->get('query'); */

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $data = $searchForm->getData();
            // dd($request->request->get('query'));
            //dd($data['query']);

            return $this->render('search/search.html.twig', [

                'search' => $articleRepository->findSearch($data['query']),
            ]);
        }

        return $this->render('home.html.twig', [

            //'search' => $search,
            'articles' => $articleRepository->findAll(),
            'searchForm' => $searchForm->createView()

            ]);
    }
}
