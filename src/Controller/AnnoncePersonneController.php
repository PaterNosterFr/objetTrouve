<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annoncePersonne", name="annoncePersonne_")
 */

class AnnoncePersonneController extends AbstractController
{
    /**
     * @Route("", name="list")
     */

    public function list(): Response
    {
        //todo : aller chercher les annonces en bdd pour affichage

        return $this->render('annoncePersonne/list.html.twig', [

        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */

    public function details(int $id): Response
    {
        //todo : aller chercher les annonces en bdd pour affichage

        return $this -> render('annoncePersonne/details.html.twig');
    }

    /**
     * @Route("/create", name="creation")
     */

    public function creation(): Response
    {
        // todo : créér le formulaire de découverte / recherche d'une personne perdu
        return $this -> render('annoncePersonne/creation.html.twig');
    }




}
