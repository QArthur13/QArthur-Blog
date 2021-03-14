<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("profile/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/commentary", name="user_commentary")
     */
    public function userCommentary()
    {
        return $this->render('user/commentary.html.twig', []);
    }

    /**
     * @Route("/share", name="user_share")
     */
    public function userShare()
    {
        return $this->render('user/share.html.twig', []);
    }

    /**
     * @Route("/like", name="user_like")
     */
    public function userLike()
    {
        return $this->render('user/like.html.twig', []);
    }

    /**
     * @Route("/{id}/information", name="user_edit")
     */
    public function userEdit(Request $request, User $user)
    {
        $user_edit_form = $this->createForm(UserType::class, $user);
        $user_edit_form->handleRequest($request);

        if ($user_edit_form->isSubmitted() && $user_edit_form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('user/information.html.twig', [

            'user' => $user,
            'form' => $user_edit_form->createView()
        ]);
    }

    /**
     * @Route("/", name="user_index")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [

            'users' => $userRepository->findAll()
        ]);
    }
}
