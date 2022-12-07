<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprietaireRepository::class)]
class Proprietaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

    #[ORM\ManyToMany(targetEntity: Chaton::class, inversedBy: 'Proprietaires_id')]
    private Collection $Chatons_id;

    public function __construct()
    {
        $this->Chatons_id = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    /**
     * @return Collection<int, Chaton>
     */
    public function getChatonsId(): Collection
    {
        return $this->Chatons_id;
    }

    public function addChatonsId(Chaton $chatonsId): self
    {
        if (!$this->Chatons_id->contains($chatonsId)) {
            $this->Chatons_id->add($chatonsId);
        }

        return $this;
    }

    public function removeChatonsId(Chaton $chatonsId): self
    {
        $this->Chatons_id->removeElement($chatonsId);

        return $this;
    }
}
