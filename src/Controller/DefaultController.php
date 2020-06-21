<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index() :Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/my-profile/{id}", name="app_profile")
     */
    public function profile(User $user): Response
    {
        if ($user->getId() == $userLoggedIn = $this->getUser()->getId()) {
            return $this->render('user/profile.html.twig', [
                'user' => $user,
            ]);
        } else {
            $this->addFlash('danger', "Vous n'avez pas accès à cet utilisateur");
            return $this->render('home.html.twig');
        }


    }
}