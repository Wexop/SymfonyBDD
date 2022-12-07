<?php

namespace App\Entity;

use App\Repository\ChatonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatonRepository::class)]
class Chaton
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Nom = null;

    #[ORM\Column]
    private ?bool $Sterilize = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Photo = null;

    #[ORM\ManyToOne(inversedBy: 'chatons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $Categorie = null;

    #[ORM\ManyToMany(targetEntity: Proprietaire::class, mappedBy: 'Chatons_id')]
    private Collection $Proprietaires_id;

    public function __construct()
    {
        $this->Proprietaires_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function isSterilize(): ?bool
    {
        return $this->Sterilize;
    }

    public function setSterilize(bool $Sterilize): self
    {
        $this->Sterilize = $Sterilize;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(?string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    /**
     * @return Collection<int, Proprietaire>
     */
    public function getProprietairesId(): Collection
    {
        return $this->Proprietaires_id;
    }

    public function addProprietairesId(Proprietaire $proprietairesId): self
    {
        if (!$this->Proprietaires_id->contains($proprietairesId)) {
            $this->Proprietaires_id->add($proprietairesId);
            $proprietairesId->addChatonsId($this);
        }

        return $this;
    }

    public function removeProprietairesId(Proprietaire $proprietairesId): self
    {
        if ($this->Proprietaires_id->removeElement($proprietairesId)) {
            $proprietairesId->removeChatonsId($this);
        }

        return $this;
    }
}
