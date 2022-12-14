<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $orderId;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user', referencedColumnName: 'uid')]
    protected User $user;

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
     * @return DateTime
     */
    public function getOrderDateTime(): DateTime
    {
        return $this->orderDateTime;
    }

    /**
     * @return String
     */
    public function getOrderDateTimeToString(): String
    {
        $date = $this->orderDateTime->format('Y-m-d H:i:s');
        return $date;
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