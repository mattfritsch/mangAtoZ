<?php

namespace App\Entity;

use App\Repository\CategRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategRepository::class)]
#[ORM\Table(name: 'categ')]
class Categ
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected int $categId;

    #[ORM\Column(type: 'string', length: 50)]
    protected string $categName;

    #[ORM\Column(type: 'string', length: 4000)]
    protected string $categDesc;

    /**
     * @return string
     */
    public function getCategDesc(): string
    {
        return $this->categDesc;
    }

    /**
     * @param string $categDesc
     */
    public function setCategDesc(string $categDesc): void
    {
        $this->categDesc = $categDesc;
    }

    /**
     * @return int
     */
    public function getCategId(): int
    {
        return $this->categId;
    }

    /**
     * @param int $categId
     */
    public function setCategId(int $categId): void
    {
        $this->categId = $categId;
    }

    /**
     * @return string
     */
    public function getCategName(): string
    {
        return $this->categName;
    }

    /**
     * @param string $categName
     */
    public function setCategName(string $categName): void
    {
        $this->categName = $categName;
    }

}