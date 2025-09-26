<?php

namespace App\Entity;

use App\Repository\BurgerRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
class Burger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(targetEntity: Pain::class, inversedBy: 'burgers')]
    #[ORM\JoinColumn(nullable: false)]
    private $pain;

    #[ORM\ManyToOne(targetEntity: Oignon::class, inversedBy: 'burgers')]
    #[ORM\JoinColumn(nullable: false)]
    private $oignon;

    #[ORM\ManyToOne(targetEntity: Sauce::class, inversedBy: 'burgers')]
    #[ORM\JoinColumn(nullable: false)]
    private $sauce;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'burger')]
    private $commentaires;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPain()
    {
        return $this->pain;
    }

    public function setPain($pain): static
    {
        $this->pain = $pain;
        return $this;
    }

    public function getOignon()
    {
        return $this->oignon;
    }

    public function setOignon($oignon): static
    {
        $this->oignon = $oignon;
        return $this;
    }

    public function getSauce()
    {
        return $this->sauce;
    }

    public function setSauce($sauce): static
    {
        $this->sauce = $sauce;
        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setBurger($this);
        }
        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getBurger() === $this) {
                $commentaire->setBurger(null);
            }
        }
        return $this;
    }
}

