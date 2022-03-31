<?php

namespace App\Entity;

use App\Repository\ObjetsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ObjetsRepository::class)
 * @method format( string $string )
 */
class Objets
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
	 * @Assert\NotBlank(message="Merci de donner un nom à l'objet trouvé (ex: clé, carte d'identité ...).")
	 * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
	 * @Assert\NotBlank(message="Merci d'indiquer l'adresse ou vous avez trouvé l'objet (même aproximativement).")
	 * @Assert\Length(max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

	/**
	 * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateModified;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/jpg" })
     */
    private $photo;

    /**
	 * @ORM\Column(type="string", length=50)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
		$date -> format ('d-m-Y');
		$this->date = $date;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
		$dateCreation = new \DateTime('now');
		$dateCreation -> format ('d-m-Y');
		$this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(?\DateTimeInterface $dateModified): self
    {
		$dateModified->format ('d-m-Y');
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
