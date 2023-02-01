<?php

namespace App\Entity;

use App\Repository\ClientTableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientTableRepository::class)]
class ClientTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $table_name = null;

    #[ORM\Column(length: 255)]
    private ?string $room_name = null;

    #[ORM\OneToMany(mappedBy: 'order_table', targetEntity: ClientOrder::class)]
    private Collection $clientOrders;

    public function __construct()
    {
        $this->clientOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTableName(): ?string
    {
        return $this->table_name;
    }

    public function setTableName(string $table_name): self
    {
        $this->table_name = $table_name;

        return $this;
    }

    public function getRoomName(): ?string
    {
        return $this->room_name;
    }

    public function setRoomName(string $room_name): self
    {
        $this->room_name = $room_name;

        return $this;
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
            $clientOrder->setOrderTable($this);
        }

        return $this;
    }

    public function removeClientOrder(ClientOrder $clientOrder): self
    {
        if ($this->clientOrders->removeElement($clientOrder)) {
            // set the owning side to null (unless already changed)
            if ($clientOrder->getOrderTable() === $this) {
                $clientOrder->setOrderTable(null);
            }
        }

        return $this;
    }
}
