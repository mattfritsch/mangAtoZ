<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
#[ORM\Table(name: 'order_product')]
class OrderProduct
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $productId;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $orderId;

    #[ORM\Column(type: 'integer')]
    protected int $qtt;

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
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
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