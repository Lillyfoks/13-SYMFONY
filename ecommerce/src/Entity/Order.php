<?php

namespace App\Entity;

use App\Entity\user;
use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?user $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,)]
    private ?\DateTimeInterface $dDate = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?adresses $adress = null;

    #[ORM\OneToOne(mappedBy: 'orderRef', cascade: ['persist', 'remove'])]
    private ?OrderDetails $orderDetails = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDDate(): ?\DateTimeInterface
    {
        return $this->dDate;
    }

    public function setDDate(\DateTimeInterface $dDate): static
    {
        $this->dDate = $dDate ?? new \DateTime();

        return $this;
    }

    public function getAdress(): ?adresses
    {
        return $this->adress;
    }

    public function setAdress(?adresses $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getOrderDetails(): ?OrderDetails
    {
        return $this->orderDetails;
    }

    public function setOrderDetails(?OrderDetails $orderDetails): static
    {
        // unset the owning side of the relation if necessary
        if ($orderDetails === null && $this->orderDetails !== null) {
            $this->orderDetails->setOrderRef(null);
        }

        // set the owning side of the relation if necessary
        if ($orderDetails !== null && $orderDetails->getOrderRef() !== $this) {
            $orderDetails->setOrderRef($this);
        }

        $this->orderDetails = $orderDetails;

        return $this;
    }
}
