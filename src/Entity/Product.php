<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\InheritanceType("JOINED");
 * @ORM\DiscriminatorColumn(name="class", type="string")
 * @ORM\DiscriminatorMap({"product" = "Product", "bundle_product"="BundleProduct"})
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
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $reference;

    /**
     * @ORM\Column(type="float")
     */
    protected $price = 0;

    /**
     * @ORM\Column(type="float")
     */
    protected $cost = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $inventory;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\BundleProduct", inversedBy="products")
     */
    private $bundles;

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
        $this->bundles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
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
     * @return Collection|BundleProduct[]
     */
    public function getBundles(): Collection
    {
        return $this->bundles;
    }

    public function addBundle(BundleProduct $bundle): self
    {
        if (!$this->bundles->contains($bundle)) {
            $this->bundles[] = $bundle;
        }

        return $this;
    }

    public function removeBundle(BundleProduct $bundle): self
    {
        if ($this->bundles->contains($bundle)) {
            $this->bundles->removeElement($bundle);
        }

        return $this;
    }

}
