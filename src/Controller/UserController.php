<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/user", name="user_")
 */

class UserController extends AbstractController
{

    /**
     * @Route("/interface/{id}", name="interface")
     */

    public function details(int $id): Response
    {
        //todo : aller chercher le profil utilisateur lié à l'id passé en paramètre

        return $this -> render('user/interface.html.twig');
    }

    /**
     * @Route("/create", name="creation")
     */

    public function creation(): Response
    {
        // todo : créer le forumulaire de création

        return $this -> render('user/creation.html.twig');
    }


}