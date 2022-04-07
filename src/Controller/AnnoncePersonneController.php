<?php

namespace App\Controller;

use App\Entity\Personnes;
use App\Form\PersonneType;
use App\Repository\PersonnesRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Constraint\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
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

    public function list(PersonnesRepository $personnesRepository): Response
    {
		$personnes = $personnesRepository -> trouverUneAnnonce ();

		return $this->render('annoncePersonne/list.html.twig', [
			"personnes" => $personnes,
		]);
    }

// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * @Route("/details/{id}", name="details")
	 */

	public function details(int $id,
							PersonnesRepository $personnesRepository): Response
	{
		$personne = $personnesRepository -> find ($id);

		return $this -> render('annoncePersonne/details.html.twig', [
			"personne" => $personne,
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
		$personne = new Personnes();

		$personne->setDateCreation ( new \DateTime());
		$personne->setStatus ('A valider');

		$personneForm = $this -> createForm (PersonneType::class, $personne);

		// NE PAS OUBLIER LE handleRequest
		$personneForm->handleRequest ($request);

		if ($personneForm -> isSubmitted () && $personneForm->isValid ()){

			$file = $personneForm -> get('photo') -> getData();

			// INSERT INTO
			$entityManager -> persist ($personne);
			$entityManager -> flush ();

			// On renomme le fichier uploadé
			$newFilename = $personne->getName()."-".$personne->getId(). ".".$file->guessExtension();

			//On déplace le fichier ou il faut
			$file -> move ($this->getParameter('upload_champ_entite_dir').'/personne/', $newFilename);

			$personne-> setPhoto( $newFilename );

			$entityManager -> persist ($personne);
			$entityManager -> flush ();

			$this->addFlash ('success', 'Annonce correctement publiée.');

			return $this->redirectToRoute ('annoncePersonne_details', [
				'id' => $personne->getId ()
			]);

		}

		return $this -> render ('annoncePersonne/creation.html.twig',[
			"personneForm" => $personneForm -> createView ()
		]);
	}

// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * @Route("/delete/{id}", name="suppression")
	 */
	public function suppression(int $id,
								Personnes $personnes,
								PersonnesRepository $personnesRepository,
								EntityManagerInterface $entityManager
								): Response
	{
		$personne = $personnesRepository -> find ($id);
		$photos = $personnes -> getPhoto();

		// le if suivant sert à retirer le fichier physiquement en plus des données de la BDD
		if ($photos) {
			// Petite boucle pour s'assurer de la présence de photo dans l'annonce
			foreach ((array) $photos as $photo )
			{
				// on génère le chemin physique de l'image - obligatoire pour le unlink
				$nomImage = $this->getParameter('upload_champ_entite_dir').'/personne/' . $photo;

				if (file_exists ($nomImage))
				{
					unlink ($nomImage);
				}
			}
		}

		//DELETE
		$entityManager->remove ($personne);
		$entityManager->flush ();

		$this->addFlash ('success', 'Annonce correctement supprimée.');
		return $this -> render('main/home.html.twig');
	}

// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * @Route("/edition/{id}", name="edition")
	 */
	public function edition(int $id,
							Personnes $personnes,
							PersonnesRepository $personnesRepository,
							EntityManagerInterface $entityManager,
							Request $request): Response
	{
		//Recuperation des anciennes data
		$personne = $personnesRepository -> find ($id);
		$oldPhoto = $personne -> getPhoto();
		$dateCreation = $personne -> getDateCreation ();

		//Creation du formulaire d'update
		$personneForm = $this -> createForm (PersonneType::class, $personne);

		// NE PAS OUBLIER LE handleRequest
		$personneForm -> handleRequest ($request);

		if ($personneForm -> isSubmitted () && $personneForm->isValid ()){

			$file = $personneForm -> get('photo') -> getData();

			// On renomme le fichier uploadé
			$newFilename = $personnes -> getName()."-".$personnes -> getId(). ".".$file -> guessExtension();

			//On déplace le fichier ou il faut
			$file -> move ($this -> getParameter('upload_champ_entite_dir').'personne/', $newFilename);


			$personnes -> setPhoto( $newFilename );
			$personnes -> setDateModified (new \DateTime());
			$personnes -> setDateCreation ($dateCreation);
			$personnes -> setStatus ('A valider');

			$photoDesuet = $this->getParameter('upload_champ_entite_dir').'personne/' . $oldPhoto;
			unlink ($photoDesuet);

			// INSERT INTO
			$entityManager -> persist ($personnes);

			$entityManager -> flush ();

			$this->addFlash ('success', 'Annonce correctement modifée.');

			return $this->redirectToRoute ('annoncePersonne_details', [
				'id' => $personnes->getId ()
			]);

		}

		return $this -> render ('annoncePersonne/creation.html.twig',[
			"personneForm" => $personneForm -> createView ()
		]);

	}

} // /!\ Ne pas effacer cette ligne. Il s'agit de la } de cloture de la classe
