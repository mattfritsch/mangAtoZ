<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'product')]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $productId;

    #[ORM\Column(type: 'string', length: 4000)]
    protected string $resume;

    #[ORM\Column(type: 'string', length: 50)]
    protected string $product_name;

    #[ORM\Column(type: 'string', length: 1000)]
    protected string $img;

    #[ORM\Column(type: 'boolean')]
    protected bool $status;

    #[ORM\Column(type: 'integer')]
    protected int $chapterNumber;

    #[ORM\Column(type: 'array')]
    protected array $categId;

    #[ORM\Column(type: 'boolean')]
    protected int $ageId;

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
    public function getProductName(): string
    {
        return $this->product_name;
    }

    /**
     * @param string $product_name
     */
    public function setProductName(string $product_name): void
    {
        $this->product_name = $product_name;
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
     * @return int
     */
    public function getCategId(): array
    {
        return $this->categId;
    }

    /**
     * @param int $categId
     */
    public function setCategId(array $categId): void
    {
        $this->categId = $categId;
    }

    /**
     * @return int
     */
    public function getAgeId(): bool
    {
        return $this->ageId;
    }

    /**
     * @param int $ageId
     */
    public function setAgeId(int $ageId): void
    {
        $this->ageId = $ageId;
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

}