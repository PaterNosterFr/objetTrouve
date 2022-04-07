<?php

namespace App\Form;

use App\Entity\Animaux;
use App\Entity\Personnes;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\OptionsResolver\OptionsResolver;



class PersonneType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'label' => 'Rapide description de la personne découverte :',
			])

			->add('lieu', TextType::class, [
				'label' => 'Où avez vous trouvé cette personne ?',
			])

			->add('date', DateType::class, [
				'html5' => true,
				'widget' => 'single_text',
				'label' => 'Quand avez-vous trouvé cette personne ?',
			])

			->add('photo', FileType::class, [
				'label' => 'Image (fichier image)',
				'mapped' => 'false',
				'data_class' => null
			])
			->add('commentaire', TextareaType::class, [
				'label' => 'Plus d\'informations à partager ?',
				'required' => FALSE,
			])

		; // ";" de fin du builder. A ne pas effacer !!

	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Personnes::class,
		]);
	}
}
