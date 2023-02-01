<?php

namespace App\Entity;

use App\Repository\ClientOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientOrderRepository::class)]
class ClientOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time_order_taking = null;

    #[ORM\ManyToOne(inversedBy: 'serveur')]
    private ?user $serveur = null;

    #[ORM\ManyToOne(inversedBy: 'clientOrders')]
    private ?ClientTable $order_table = null;

    #[ORM\ManyToMany(targetEntity: Dish::class, inversedBy: 'clientOrders')]
    private Collection $dishes_order;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $total_prices = null;

    #[ORM\Column(length: 255)]
    private ?string $order_status = null;

    public function __construct()
    {
        $this->dishes_order = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeOrderTaking(): ?\DateTimeInterface
    {
        return $this->time_order_taking;
    }

    public function setTimeOrderTaking(\DateTimeInterface $time_order_taking): self
    {
        $this->time_order_taking = $time_order_taking;

        return $this;
    }

    public function getServeur(): ?user
    {
        return $this->serveur;
    }

    public function setServeur(?user $serveur): self
    {
        $this->serveur = $serveur;

        return $this;
    }

    public function getOrderTable(): ?ClientTable
    {
        return $this->order_table;
    }

    public function setOrderTable(?ClientTable $order_table): self
    {
        $this->order_table = $order_table;

        return $this;
    }

    /**
     * @return Collection<int, Dish>
     */
    public function getDishesOrder(): Collection
    {
        return $this->dishes_order;
    }

    public function addDishesOrder(Dish $dishesOrder): self
    {
        if (!$this->dishes_order->contains($dishesOrder)) {
            $this->dishes_order->add($dishesOrder);
        }

        return $this;
    }

    public function removeDishesOrder(Dish $dishesOrder): self
    {
        $this->dishes_order->removeElement($dishesOrder);

        return $this;
    }

    public function getTotalPrices(): ?string
    {
        return $this->total_prices;
    }

    public function setTotalPrices(string $total_prices): self
    {
        $this->total_prices = $total_prices;

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->order_status;
    }

    public function setOrderStatus(string $order_status): self
    {
        $this->order_status = $order_status;

        return $this;
    }
}
