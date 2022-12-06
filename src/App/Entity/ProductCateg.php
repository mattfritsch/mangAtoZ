<?php

namespace App\Entity;

use App\Repository\ProductCategRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: ProductCategRepository::class)]
#[ORM\Table(name: 'product_categ')]
class ProductCateg
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $productCategId;

    #[ManyToOne(targetEntity: Product::class)]
    #[JoinColumn(name: 'product', referencedColumnName: 'productId')]
    protected Product $product;

    #[ManyToOne(targetEntity: Categ::class)]
    #[JoinColumn(name: 'categ', referencedColumnName: 'categId')]
    protected Categ $categ;

    /**
     * @return int
     */
    public function getProductCategId(): int
    {
        return $this->productCategId;
    }

    /**
     * @param int $productCategId
     */
    public function setProductCategId(int $productCategId): void
    {
        $this->productCategId = $productCategId;
    }

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


}