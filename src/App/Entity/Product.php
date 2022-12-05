<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'product')]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $productId;

    #[ORM\Column(type: 'string', length: 4000)]
    protected string $resume;

    #[ORM\Column(type: 'string', length: 200)]
    protected string $productName;

    #[ORM\Column(type: 'string', length: 1000)]
    protected string $img;

    #[ORM\Column(type: 'boolean')]
    protected bool $status;

    #[ORM\Column(type: 'integer')]
    protected int $chapterNumber;

    #[ManyToOne(targetEntity: Categ::class)]
    #[JoinColumn(name: 'categ', referencedColumnName: 'categId')]
    protected Categ $categ;

    /**
     * @return Categ
     */
    public function getCateg(): Categ
    {
        return $this->categ;
    }

    /**
     * @param Categ $categ
     */
    public function setCateg(Categ $categ): void
    {
        $this->categ = $categ;
    }

    #[ORM\Column(type: 'boolean')]
    protected bool $ageRank;

    /**
     * @return bool
     */
    public function isAgeRank(): bool
    {
        return $this->ageRank;
    }

    /**
     * @param bool $ageRank
     */
    public function setAgeRank(bool $ageRank): void
    {
        $this->ageRank = $ageRank;
    }

    #[ORM\Column(type: 'float')]
    protected float $averageRating;

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getResume(): string
    {
        return $this->resume;
    }

    /**
     * @param string $resume
     */
    public function setResume(string $resume): void
    {
        $this->resume = $resume;
    }

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    /**
     * @param string $img
     */
    public function setImg(string $img): void
    {
        $this->img = $img;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getChapterNumber(): int
    {
        return $this->chapterNumber;
    }

    /**
     * @param int $chapterNumber
     */
    public function setChapterNumber(int $chapterNumber): void
    {
        $this->chapterNumber = $chapterNumber;
    }

    /**
     * @return float
     */
    public function getAverageRating(): float
    {
        return $this->averageRating;
    }

    /**
     * @param float $averageRating
     */
    public function setAverageRating(float $averageRating): void
    {
        $this->averageRating = $averageRating;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     */
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

}