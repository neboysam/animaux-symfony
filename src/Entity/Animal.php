<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=AnimalRepository::class)
 * @Vich\Uploadable
 */
class Animal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="animal_image", fileNameProperty="image")
     */
    private $imageFile;

    public function setImageFile(?File $imageFile = null): self
    {
        $this->imageFile = $imageFile;
        if($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;

        /* if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        } */
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @ORM\Column(type="integer")
     */
    private $poids;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dangereux;

    /**
     * @ORM\ManyToOne(targetEntity=Famille::class, inversedBy="animaux")
     * @ORM\JoinColumn(nullable=true, name="famille_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $famille;

    /**
     * @ORM\ManyToMany(targetEntity=Continent::class, mappedBy="animaux") 
     */
    private $continents;

    /**
     * @ORM\OneToMany(targetEntity=Dispose::class, mappedBy="animal")
     */
    private $disposes;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->continents = new ArrayCollection();
        $this->disposes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getDangereux(): ?bool
    {
        return $this->dangereux;
    }

    public function setDangereux(bool $dangereux): self
    {
        $this->dangereux = $dangereux;

        return $this;
    }

    public function getFamille(): ?Famille
    {
        return $this->famille;
    }

    public function setFamille(?Famille $famille): self
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * @return Collection<int, Continent>
     */
    public function getContinents(): Collection
    {
        return $this->continents;
    }

    public function addContinent(Continent $continent): self
    {
        if (!$this->continents->contains($continent)) {
            $this->continents[] = $continent;
            $continent->addAnimaux($this);
        }

        return $this;
    }

    public function removeContinent(Continent $continent): self
    {
        if ($this->continents->removeElement($continent)) {
            $continent->removeAnimaux($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Dispose>
     */
    public function getDisposes(): Collection
    {
        return $this->disposes;
    }

    public function addDispose(Dispose $dispose): self
    {
        if (!$this->disposes->contains($dispose)) {
            $this->disposes[] = $dispose;
            $dispose->setAnimal($this);
        }

        return $this;
    }

    public function removeDispose(Dispose $dispose): self
    {
        if ($this->disposes->removeElement($dispose)) {
            // set the owning side to null (unless already changed)
            if ($dispose->getAnimal() === $this) {
                $dispose->setAnimal(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /* public function __toString(): string
    {
        return $this->getNom();
    } */
}
