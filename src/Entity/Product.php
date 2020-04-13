<?php

namespace App\Entity;

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
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id_presta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_manufacturer;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_category_default;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getIdPresta()
    {
        return $this->id_presta;
    }

    /**
     * @param mixed $id_presta
     */
    public function setIdPresta($id_presta): void
    {
        $this->id_presta = $id_presta;
    }

    public function getIdManufacturer(): ?int
    {
        return $this->id_manufacturer;
    }

    public function setIdManufacturer(?int $id_manufacturer): self
    {
        $this->id_manufacturer = $id_manufacturer;

        return $this;
    }

    public function getIdCategoryDefault(): ?int
    {
        return $this->id_category_default;
    }

    public function setIdCategoryDefault(int $id_category_default): self
    {
        $this->id_category_default = $id_category_default;

        return $this;
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
}
