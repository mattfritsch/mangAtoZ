<?php

namespace App\Entity;

use App\Repository\CartProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartProductRepository::class)]
#[ORM\Table(name: 'cart_product')]
class CartProduct
{


    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $productId;

    #[ORM\Column(type: 'integer')]
    protected int $chapterId;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $cartId;

    #[ORM\Column(type: 'integer')]
    protected int $qtt;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    /**
     * @ManyToOne(targetEntity="CartProduct")
     * @JoinColumns({
     *     @JoinColumn(name="property1", referencedColumnName="property1"),
     *     @JoinColumn(name="property2", referencedColumnName="property2")
     * })
     **/
    protected int $id;

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
     * @return int
     */
    public function getChapterId(): int
    {
        return $this->chapterId;
    }

    /**
     * @param int $productId
     */
    public function setChapterId(int $chapterId): void
    {
        $this->chapterId = $chapterId;
    }


    /**
     * @return int
     */
    public function getCartId(): int
    {
        return $this->cartId;
    }

    /**
     * @param int $cartId
     */
    public function setCartId(int $cartId): void
    {
        $this->cartId = $cartId;
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