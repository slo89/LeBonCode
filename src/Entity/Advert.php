<?php

namespace App\Entity;

use App\Repository\AdvertRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdvertRepository::class)
 *
 */
class Advert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $salePrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cityOfSale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onLine;

    public function __construct()
    {
        $this->setOnLine(True);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSalePrice(): ?int
    {
        return $this->salePrice;
    }

    public function setSalePrice(int $salePrice): self
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCityOfSale(): ?string
    {
        return $this->cityOfSale;
    }

    public function setCityOfSale(string $cityOfSale): self
    {
        $this->cityOfSale = $cityOfSale;

        return $this;
    }

    public function getOnLine(): ?bool
    {
        return $this->onLine;
    }

    public function setOnLine(bool $onLine): self
    {
        $this->onLine = $onLine;

        return $this;
    }
}
