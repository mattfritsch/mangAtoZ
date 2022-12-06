<?php

namespace App\Entity;

use App\Repository\ChapterRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: ChapterRepository::class)]
#[ORM\Table(name: 'chapter')]
class Chapter
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $chapterId;

    #[ManyToOne(targetEntity: Product::class)]
    #[JoinColumn(name: 'product', referencedColumnName: 'productId')]
    protected Product $product;

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    #[ORM\Column(type: 'integer')]
    protected int $stock;

    #[ORM\Column(type: 'float')]
    protected float $chapterPrice;

    /**
     * @return int
     */
    public function getChapterId(): int
    {
        return $this->chapterId;
    }

    /**
     * @param int $chapterId
     */
    public function setChapterId(int $chapterId): void
    {
        $this->chapterId = $chapterId;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return float
     */
    public function getChapterPrice(): float
    {
        return $this->chapterPrice;
    }

    /**
     * @param float $chapterPrice
     */
    public function setChapterPrice(float $chapterPrice): void
    {
        $this->chapterPrice = $chapterPrice;
    }

}