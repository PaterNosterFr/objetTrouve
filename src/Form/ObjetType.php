<?php

namespace App\Form;

use App\Entity\Objets;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Image;


class ObjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
				'label' => 'Quel objet avez-vous trouvé ?',
			])

            ->add('lieu', TextareaType::class, [
				'label' => 'Où avez vous trouvé cet objet ?',
			])

            ->add('date', DateType::class, [
				'html5' => true,
				'widget' => 'single_text',
				'label' => 'Quand avez-vous trouvé cet objet ?',
			])

            ->add('photo', FileType::class, [
				'mapped' => 'false',
			])

			->add ('status', ChoiceType::class,[
				'choices' => [
					'Trouvé' => 'Trouvé',
					'Perdu' => 'Perdu'
				],
				'multiple' => FALSE
			])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Objets::class,
        ]);
    }
}
