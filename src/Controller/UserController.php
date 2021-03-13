<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
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
     * @Route("/user/information", name="user_edit")
     */
    public function userEdit(Request $request, User $user)
    {
        $user_edit_form = $this->createForm(UserType::class, $user);
        $user_edit_form->handleRequest($request);

        if ($user_edit_form->isSubmitted() && $user_edit_form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('user/information.htlm.twig', [

            'user' => $user,
            'form' => $user_edit_form->createView()
        ]);
    }

    /**
     * @Route("/", name="user_index")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', []);
    }
}
