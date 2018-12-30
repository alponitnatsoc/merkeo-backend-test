<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price = 0.0;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cost = 0.0;

    /**
     * @ORM\Column(type="integer")
     */
    private $inventory = 0;

    /**
     * 0 - inactive
     * 1 - active
     * @ORM\Column(type="integer")
     */
    private $status = 0;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductBundle", mappedBy="products")
     */
    private $productBundles;

    public function __construct()
    {
        $this->productBundles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getInventory(): ?int
    {
        return $this->inventory;
    }

    public function setInventory(int $inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|ProductBundle[]
     */
    public function getProductBundles(): Collection
    {
        return $this->productBundles;
    }

    public function addProductBundle(ProductBundle $productBundle): self
    {
        if (!$this->productBundles->contains($productBundle)) {
            $this->productBundles[] = $productBundle;
            $productBundle->addProduct($this);
        }

        return $this;
    }

    public function removeProductBundle(ProductBundle $productBundle): self
    {
        if ($this->productBundles->contains($productBundle)) {
            $this->productBundles->removeElement($productBundle);
            $productBundle->removeProduct($this);
        }

        return $this;
    }

    /**
     * @param int $units to increase or decrease inventory
     * increases the inventory amount in the units passed as parameter, if the units are negative inventory is decreased to a max of 0
     * @return Product
     */
    public function addInventory(int $units) : self
    {
        $inventory = $this->inventory+$units >= 0 ? $this->inventory+$units : 0;
        $this->setInventory($inventory);
        return $this;
    }

    public function activateProduct() : self
    {
        $this->setStatus(1);
        return $this;
    }
}
