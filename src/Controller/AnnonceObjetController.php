<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonceObjet", name="annonceObjet_")
 */

class AnnonceObjetController extends AbstractController
{
    /**
     * @Route("", name="list")
     */

    public function list(): Response
    {
        //todo : aller chercher les annonces en bdd pour affichage

        return $this->render('annonceObjet/list.html.twig', [

        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */

    public function details(int $id): Response
    {
        //todo : aller chercher les annonces en bdd pour affichage

        return $this -> render('annonceObjet/details.html.twig');
    }

    /**
     * @Route("/create", name="creation")
     */

    public function creation(): Response
    {

        return $this -> render('annonceObjet/creation.html.twig');
    }

}
