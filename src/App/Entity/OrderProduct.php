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
    #[ManyToOne(targetEntity: Chapter::class)]
    #[JoinColumn(name: 'chapter', referencedColumnName: 'chapterId')]
    protected Chapter $chapter;

    #[ORM\Id]
    #[ManyToOne(targetEntity: Order::class)]
    #[JoinColumn(name: 'order', referencedColumnName: 'orderId')]
    protected Order $order;

    #[ORM\Column(type: 'integer')]
    protected int $qtt;

    /**
     * @return Chapter
     */
    public function getChapter(): Chapter
    {
        return $this->chapter;
    }

    /**
     * @param Chapter $chapter
     */
    public function setChapter(Chapter $chapter): void
    {
        $this->chapter = $chapter;
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