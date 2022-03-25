<?php

namespace App\Controller;

use App\Entity\Objets;
use App\Form\ObjetType;
use App\Repository\ObjetsRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;


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

    public function list(ObjetsRepository $objetsRepository): Response
    {
        //SELECT *
		// $objets = $objetsRepository -> findAll ();

		//SELECT * ([WHERE], [TRIE], limiteDeResultatAffiché, àPartirDe... )
		//$objets = $objetsRepository->findBy ([], ['dateCreation' => 'DESC'], 20);

		//Usage de la fonction personnalisée crée dans le repository:
		$objets = $objetsRepository -> trouverUneAnnonce ();

        return $this->render('annonceObjet/list.html.twig', [
			"objets" => $objets,
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */

    public function details(int $id,
							ObjetsRepository $objetsRepository): Response
    {
        $objet = $objetsRepository->find ($id);

        return $this -> render('annonceObjet/details.html.twig', [
			"objet" => $objet,
		]);
    }

    /**
     * @Route("/create", name="creation")
     */

    public function creation(Request $request): Response
    {
		$objet = new Objets();
		$objetForm = $this -> createForm (ObjetType::class, $objet);

		return $this -> render ('annonceObjet/creation.html.twig',[
			"objetForm" => $objetForm -> createView ()
		]);

/*
		$objet->setDateCreation (new \DateTime());
		$objet->setStatus ("Active");


		// INSERT INTO
		$entityManager->persist ($objet);
		$entityManager->flush ();


        return $this -> render('annonceObjet/creation.html.twig');
*/
    }

	public function suppression(int $id,
								EntityManagerInterface $entityManager,
								Request $request,
								ObjetsRepository $objetsRepository): Response
	{
		$objet = $objetsRepository->find ($id);

		//DELETE
		$entityManager->remove ($objet);
		$entityManager->flush ();

		return $this -> render('annonceObjet/list.html.twig');
	}

}
