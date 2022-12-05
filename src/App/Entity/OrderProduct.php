<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
#[ORM\Table(name: 'order_product')]
class OrderProduct
{
    #[ORM\Id]
    #[ManyToOne(targetEntity: Product::class)]
    #[JoinColumn(name: 'product', referencedColumnName: 'productId')]
    protected Product $product;

    #[ORM\Id]
    #[ManyToOne(targetEntity: Order::class)]
    #[JoinColumn(name: 'order', referencedColumnName: 'orderId')]
    protected Order $order;

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
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    #[ORM\Column(type: 'integer')]
    protected int $qtt;



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