<?php

namespace App\Controller;


use App\Entity\Animaux;
use App\Form\AnimalType;
use App\Repository\AnimauxRepository;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Constraint\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
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

	public function list(AnimauxRepository $animauxRepository): Response
	{

		$animaux = $animauxRepository -> trouverUneAnnonce ();

		return $this->render('annonceAnimal/list.html.twig', [
			"animaux" => $animaux,
		]);
	}

    /**
     * @Route("/details/{id}", name="details")
     */

	public function details(int $id,
							AnimauxRepository $animauxRepository): Response
	{
		$animal = $animauxRepository->find ($id);

		return $this -> render('annonceAnimal/details.html.twig', [
			"animal" => $animal,
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
		$animal = new Animaux();

		$animal->setDateCreation ( new \DateTime());
		$animal->setStatus ('A valider');

		$animalForm = $this -> createForm (AnimalType::class, $animal);

		// NE PAS OUBLIER LE handleRequest
		$animalForm->handleRequest ($request);

		if ($animalForm -> isSubmitted () && $animalForm->isValid ()){

			$file = $animalForm -> get('photo') -> getData();

			// INSERT INTO
			$entityManager -> persist ($animal);
			$entityManager -> flush ();

			// On renomme le fichier uploadé
			$newFilename = $animal->getName()."-".$animal->getId(). ".".$file->guessExtension();

			//On déplace le fichier ou il faut
			$file -> move ($this->getParameter('upload_champ_entite_dir').'/animal/', $newFilename);

			$animal-> setPhoto( $newFilename );

			$entityManager -> persist ($animal);
			$entityManager -> flush ();

			$this->addFlash ('success', 'Annonce correctement publiée.');

			return $this->redirectToRoute ('annonceAnimal_details', [
				'id' => $animal->getId (),
			]);

		}


		return $this -> render ('annonceAnimal/creation.html.twig',[
			"animalForm" => $animalForm -> createView ()
		]);

	}

	/**
	 * @Route("/delete/{id}", name="suppression")
	 */

	public function suppression(int $id,
								Animaux $animaux,
								EntityManagerInterface $entityManager,
								AnimauxRepository $animauxRepository): Response
	{
		$animal = $animauxRepository->find ($id);
		$photos = $animaux->getPhoto();

		// le if suivant sert à retirer le fichier physiquement en plus des données de la BDD
		if ($photos) {
			// Petite boucle pour s'assurer de la présence de photo dans l'annonce
			foreach ((array) $photos as $photo )
			{
				// on génère le chemin physique de l'image - obligatoire pour le unlink
				$nomImage = $this->getParameter('upload_champ_entite_dir').'/animal/' . $photo;

				if (file_exists ($nomImage))
				{
					unlink ($nomImage);
				}
			}
		}

		//DELETE
		$entityManager->remove ($animal);
		$entityManager->flush ();

		$this->addFlash ('success', 'Annonce correctement supprimée.');
		return $this -> render('main/home.html.twig');
	}

	/**
	 * @Route("/edition/{id}", name="edition")
	 */
	public function edition(int $id,
							Animaux $animaux,
							EntityManagerInterface $entityManager,
							AnimauxRepository $animauxRepository,
							Request $request): Response
	{

		$animal = $animauxRepository->find ($id);
		$oldPhoto = $animaux->getPhoto();
		$dateCreation = $animaux->getDateCreation ();

		$animalForm = $this -> createForm (AnimalType::class, $animal);

		// NE PAS OUBLIER LE handleRequest
		$animalForm->handleRequest ($request);

		if ($animalForm -> isSubmitted () && $animalForm->isValid ())
		{
			$newPhoto = $animalForm -> get('photo') -> getData();

			// On renomme le fichier uploadé
			$newFilename = $animal->getName()."-".$animal->getId(). ".".$newPhoto->guessExtension();

			//On déplace le fichier ou il faut
			$newPhoto -> move ($this->getParameter('upload_champ_entite_dir').'animal/', $newFilename);

			$animal-> setPhoto( $newFilename );
			$animal -> setDateModified (new \DateTime());
			$animal -> setDateCreation ($dateCreation);
			$animal -> setStatus ('A valider');

			$photoDesuet = $this->getParameter('upload_champ_entite_dir').'animal/' . $oldPhoto;
			unlink ($photoDesuet);

			// INSERT INTO
			$entityManager -> persist ($animal);
			$entityManager -> flush ();

			$this->addFlash ('success', 'Annonce correctement modifée.');

			return $this->redirectToRoute ('annonceAnimal_details', [
				'id' => $animal->getId ()
			]);

		}

		return $this -> render ('annonceAnimal/creation.html.twig',[
			"animalForm" => $animalForm -> createView ()
		]);

	}

}
