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

    #[ORM\ManyToMany(targetEntity: Oignon::class, inversedBy: 'burgers')]
    private $oignon;

    #[ORM\ManyToMany(targetEntity: Sauce::class, inversedBy: 'burgers')]
    private $sauce;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'burger')]
    private $commentaires;

    public function __construct()
    {
        $this->oignon = new ArrayCollection();
        $this->sauce = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Oignon>
     */
    public function getOignon(): Collection
    {
        return $this->oignon;
    }

    public function addOignon(Oignon $oignon): static
    {
        if (!$this->oignon->contains($oignon)) {
            $this->oignon[] = $oignon;
        }
        return $this;
    }

    public function removeOignon(Oignon $oignon): static
    {
        $this->oignon->removeElement($oignon);
        return $this;
    }

    /**
     * @return Collection<int, Sauce>
     */
    public function getSauce(): Collection
    {
        return $this->sauce;
    }

    public function addSauce(Sauce $sauce): static
    {
        if (!$this->sauce->contains($sauce)) {
            $this->sauce[] = $sauce;
        }
        return $this;
    }

    public function removeSauce(Sauce $sauce): static
    {
        $this->sauce->removeElement($sauce);
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
