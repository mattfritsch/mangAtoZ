<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'order')]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $orderId;

    #[ORM\Column(type: 'integer')]
    protected int $uid;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $orderDateTime;

    #[ORM\Column(type: 'float')]
    protected float $totalPrice;

    #[ORM\Column(type: 'boolean')]
    protected bool $delivered;

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
    public function getUid(): int
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @return DateTime
     */
    public function getOrderDateTime(): DateTime
    {
        return $this->orderDateTime;
    }

    /**
     * @param DateTime $orderDateTime
     */
    public function setOrderDateTime(DateTime $orderDateTime): void
    {
        $this->orderDateTime = $orderDateTime;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     */
    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return bool
     */
    public function isDelivered(): bool
    {
        return $this->delivered;
    }

    /**
     * @param bool $delivered
     */
    public function setDelivered(bool $delivered): void
    {
        $this->delivered = $delivered;
    }

}