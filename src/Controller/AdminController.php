<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommentaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/admin")
 */
class AdminController extends AbstractController
{
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
