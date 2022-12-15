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
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $orderProductId;

    #[ManyToOne(targetEntity: Chapter::class)]
    #[JoinColumn(name: 'chapter', referencedColumnName: 'chapterId')]
    protected Chapter $chapter;

    #[ORM\Column(type: 'integer')]
    protected int $qtt;

    /**
     * @return int
     */
    public function getOrderProductId(): int
    {
        return $this->orderProductId;
    }

    /**
     * @param int $orderProductId
     */
    public function setOrderProductId(int $orderProductId): void
    {
        $this->orderProductId = $orderProductId;
    }

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