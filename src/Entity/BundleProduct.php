<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BundleProductRepository")
 */
class BundleProduct extends Product
{

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="bundles")
     */
    private $products;

    /**
     * get Class
     * returns the name of the class for the child entities
     * @return string
     */
    public function getClass(){
        $path = explode('\\', __CLASS__);
        return array_pop($path);
    }

    public function __construct()
    {
        parent::__construct();
        $this->products = new ArrayCollection();
    }

    public function calculateCost(): self
    {
        $totalCost = 0;
        /** @var Product $product */
        foreach ($this->products as $product){
            $totalCost += $product->getCost();
        }
        $this->setCost($totalCost);
        return $this;
    }
    public function calculatePrice(): self
    {
        $totalPrice = 0;
        /** @var Product $product */
        foreach ($this->products as $product){
            $totalPrice += $product->getPrice();
        }
        $this->setPrice($totalPrice);
        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addBundle($this);
        }

        $this->calculateCost();
        $this->calculatePrice();

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeBundle($this);
        }

        $this->calculateCost();
        $this->calculatePrice();

        return $this;
    }

}
