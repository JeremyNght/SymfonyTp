<?php

namespace App\Entity;

use App\Repository\DishRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DishRepository::class)]
class Dish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column]
    private ?int $Calories = null;

    #[ORM\Column]
    private ?float $Price = null;

    #[ORM\Column(length: 255)]
    private ?string $Image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?bool $Sticky = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\ManyToOne(inversedBy: 'dishes')]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Allergen::class, inversedBy: 'dishes', cascade: ['persist'])]
    private Collection $allergen;

    #[ORM\ManyToMany(targetEntity: ClientOrder::class, mappedBy: 'dishes_order')]
    private Collection $clientOrders;

    public function __construct()
    {
        $this->allergen = new ArrayCollection();
        $this->clientOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->Calories;
    }

    public function setCalories(int $Calories): self
    {
        $this->Calories = $Calories;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function isSticky(): ?bool
    {
        return $this->Sticky;
    }

    public function setSticky(bool $Sticky): self
    {
        $this->Sticky = $Sticky;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Allergen>
     */
    public function getAllergen(): Collection
    {
        return $this->allergen;
    }

    public function addAllergen(Allergen $allergen): self
    {
        if (!$this->allergen->contains($allergen)) {
            $this->allergen->add($allergen);
        }

        return $this;
    }

    public function removeAllergen(Allergen $allergen): self
    {
        $this->allergen->removeElement($allergen);

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, ClientOrder>
     */
    public function getClientOrders(): Collection
    {
        return $this->clientOrders;
    }

    public function addClientOrder(ClientOrder $clientOrder): self
    {
        if (!$this->clientOrders->contains($clientOrder)) {
            $this->clientOrders->add($clientOrder);
            $clientOrder->addDishesOrder($this);
        }

        return $this;
    }

    public function removeClientOrder(ClientOrder $clientOrder): self
    {
        if ($this->clientOrders->removeElement($clientOrder)) {
            $clientOrder->removeDishesOrder($this);
        }

        return $this;
    }
}
