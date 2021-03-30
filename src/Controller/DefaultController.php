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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
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
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('home.html.twig', [
            'liste' => $articleRepository->findAll()
            ]);
    }
}
