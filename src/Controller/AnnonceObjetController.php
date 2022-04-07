<?php

namespace App\Controller;

use App\Entity\Objets;
use App\Form\ObjetType;

use App\Repository\ObjetsRepository;

use Doctrine\ORM\EntityManagerInterface;

use Exception;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\DateType;
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

// ---------------------------------------------------------------------------------------------------------------------

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
		$objet->setStatus ('A valider');

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

// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * @Route("/delete/{id}", name="suppression")
	 */
	public function suppression(int $id,
								Objets $objets,
								EntityManagerInterface $entityManager,
								ObjetsRepository $objetsRepository): Response
	{
		$objet = $objetsRepository->find ($id);
		$photos = $objets->getPhoto();

		// le if suivant sert à retirer le fichier physiquement en plus des données de la BDD
		if ($photos) {
			// Petite boucle pour s'assurer de la présence de photo dans l'annonce
			foreach ((array) $photos as $photo )
			{
				// on génère le chemin physique de l'image - obligatoire pour le unlink
				$nomImage = $this->getParameter('upload_champ_entite_dir').'/objet/' . $photo;

				if (file_exists ($nomImage))
				{
					unlink ($nomImage);
				}
			}
		}

		//DELETE
		$entityManager->remove ($objet);
		$entityManager->flush ();

		$this->addFlash ('success', 'Annonce correctement supprimée.');
		return $this -> render('main/home.html.twig');
	}

// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * @Route("/edition/{id}", name="edition")
	 */
	public function edition(int $id,
							Objets $objets,
							EntityManagerInterface $entityManager,
							ObjetsRepository $objetsRepository,
							Request $request): Response
	{
		//Recuperation des anciennes data
		$objet = $objetsRepository->find ($id);
		$oldPhoto = $objet ->getPhoto();
		$dateCreation = $objet->getDateCreation ();

		//Creation du formulaire d'update
		$objetForm = $this -> createForm (ObjetType::class, $objets);

		// NE PAS OUBLIER LE handleRequest
		$objetForm->handleRequest ($request);

		if ($objetForm -> isSubmitted () && $objetForm->isValid ())
		{
			$newPhoto = $objetForm -> get('photo') -> getData();

			// On renomme le fichier uploadé
			$newFilename = $objets->getName()."-".$objets->getId(). ".".$newPhoto->guessExtension();

			//On déplace le fichier ou il faut
			$newPhoto -> move ($this->getParameter('upload_champ_entite_dir').'objet/', $newFilename);

			$objets-> setPhoto( $newFilename );
			$objets -> setDateModified (new \DateTime());
			$objets -> setDateCreation ($dateCreation);
			$objets -> setStatus ('A valider');

			$photoDesuet = $this->getParameter('upload_champ_entite_dir').'objet/' . $oldPhoto;
			unlink ($photoDesuet);

			// INSERT INTO
			$entityManager -> persist ($objets);
			$entityManager -> flush ();

			$this->addFlash ('success', 'Annonce correctement modifée.');

			return $this->redirectToRoute ('annonceObjet_details', [
				'id' => $objets->getId ()
			]);

		}

		return $this -> render ('annonceObjet/creation.html.twig',[
			"objetForm" => $objetForm -> createView ()
		]);

	}

} // /!\ Ne pas effacer cette ligne. Il s'agit de la } de cloture de la classe
