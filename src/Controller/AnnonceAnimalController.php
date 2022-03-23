<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonceAnimal", name="annonceAnimal_")
 */

class AnnonceAnimalController extends AbstractController
{
    /**
     * @Route("", name="list")
     */

    public function list(): Response
    {
        //todo : aller chercher les annonces en bdd pour affichage

        return $this->render('annonceAnimal/list.html.twig', [

        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */

    public function details(int $id): Response
    {
        //todo : aller chercher les annonces en bdd pour affichage

        return $this -> render('annonceAnimal/details.html.twig');
    }

    /**
     * @Route("/create", name="creation")
     */

    public function creation(): Response
    {
        // todo : créér le formulaire de découverte / recherche d'animaux perdus
        return $this -> render('annonceAnimal/creation.html.twig');
    }


}
