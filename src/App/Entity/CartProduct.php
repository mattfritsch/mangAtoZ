<?php

namespace App\Entity;

use App\Repository\CartProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: CartProductRepository::class)]
#[ORM\Table(name: 'cart_product')]
class CartProduct
{
    #[ORM\Id]
    #[ManyToOne(targetEntity: Product::class)]
    #[JoinColumn(name: 'product', referencedColumnName: 'productId')]
    protected Product $product;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user', referencedColumnName: 'uid')]
    protected User $user;

    #[ORM\Column(type: 'integer')]
    protected int $qtt;

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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getQtt(): int
    {
        return $this->qtt;
    }

    /**
     * @param int $qtt
     */
    public function setQtt(int $qtt): void
    {
        $this->qtt = $qtt;
    }

}