<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Products::class, inversedBy: 'orderDetails')]
    private Collection $product;

    #[ORM\OneToOne(inversedBy: 'orderDetails', cascade: ['persist', 'remove'])]
    private ?order $orderRef = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $TotalPrice = null;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Products $product): static
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
        }

        return $this;
    }

    public function removeProduct(Products $product): static
    {
        $this->product->removeElement($product);

        return $this;
    }

    public function getOrderRef(): ?order
    {
        return $this->orderRef;
    }

    public function setOrderRef(?order $orderRef): static
    {
        $this->orderRef = $orderRef;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->TotalPrice;
    }

    public function setTotalPrice(float $TotalPrice): static
    {
        $this->TotalPrice = $TotalPrice;

        return $this;
    }
}
