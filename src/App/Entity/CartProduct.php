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
    #[ManyToOne(targetEntity: Chapter::class)]
    #[JoinColumn(name: 'chapter', referencedColumnName: 'chapterId')]
    protected Chapter $chapter;

    #[ORM\Id]
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user', referencedColumnName: 'uid')]
    protected User $user;

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