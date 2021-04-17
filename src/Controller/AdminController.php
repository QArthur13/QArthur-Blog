<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaryRepository;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/suppresion_N°{id}", name="article_delete")
     */
    public function deleteArticle(Article $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute("admin_articles");
    }

    /**
     * @Route("/article_N°{id}", name="article_edit")
     */
    public function editArticle(Article $article, Request $request)
    {
        $articleEditForm = $this->createForm(ArticleType::class, $article);
        $articleEditForm->handleRequest($request);

        //dd($article->getUser());

        if ($articleEditForm->isSubmitted() && $articleEditForm->isValid()) {

            dd($articleEditForm->getData());
            
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
            //$data = $articleForm->getData();

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
                //->setUser($this->getUser())
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
     * @Route("/commentary", name="admin_commentary")
     */
    public function commentaryManagement(CommentaryRepository $commentaryRepository)
    {
        return $this->render('admin/commentary.html.twig', [

            'lists' => $commentaryRepository->findAll()
        ]);
    }

    /**
     * @Route("/articles", name="admin_articles")
     */
    public function allArticles(ArticleRepository $articleRepository)
    {
        return $this->render('admin/articles.html.twig', [

            'lists' => $articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/", name="admin_index")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'lists' => $articleRepository->findAll(),
        ]);
    }
}
