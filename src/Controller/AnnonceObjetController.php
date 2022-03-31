<?php

namespace App\Controller;

use App\Entity\Objets;
use App\Form\ObjetType;

use App\Repository\ObjetsRepository;

use Doctrine\ORM\EntityManagerInterface;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
	 * @throws Exception
	 */

    public function creation(
		EntityManagerInterface $entityManager,
		Request $request): Response
    {
		$objet = new Objets();

		$objet->setDateCreation ( new \DateTime());

		$objetForm = $this -> createForm (ObjetType::class, $objet);

		// NE PAS OUBLIER LE handleRequest
		$objetForm->handleRequest ($request);

		if ($objetForm -> isSubmitted () && $objetForm->isValid ()){

			$file = $objetForm -> get('photo') -> getData();

			// INSERT INTO
			$entityManager -> persist ($objet);
			$entityManager -> flush ();

			// On renomme le fichier uploadé
			$newFilename = $objet->getName()."-".$objet->getId(). ".".$file->guessExtension();

			//On déplace le fichier ou il faut
			$file -> move ($this->getParameter('upload_champ_entite_dir').'/objet/', $newFilename);

			$objet-> setPhoto( $newFilename );

			$entityManager -> persist ($objet);
			$entityManager -> flush ();

			$this->addFlash ('success', 'Annonce correctement publiée.');

			return $this->redirectToRoute ('annonceObjet_details', [
				'id' => $objet->getId ()
			]);

		}


		return $this -> render ('annonceObjet/creation.html.twig',[
			"objetForm" => $objetForm -> createView ()
		]);

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

	/**
	 * @Route("/edition/{id}", name="edition")
	 */
	public function edition(int $id,
							EntityManagerInterface $entityManager,
							Request $request,
							ObjetsRepository $objetsRepository): Response
	{
		$objet = $objetsRepository->find ($id);

		$objet->setDateModified ( new \DateTime());

		$objetForm = $this -> createForm (ObjetType::class, $objet);

		$objetForm->handleRequest ($request);

		return $this -> render('annonceObjet/edition.html.twig', [
			"objet" => $objet,
		]);
	}
}
