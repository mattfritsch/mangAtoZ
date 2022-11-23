<?php

namespace App\Entity;

use App\Repository\SubtypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubtypeRepository::class)]
#[ORM\Table(name: 'subtype')]
class Subtype
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $subtypeId;

    #[ORM\Column(type: 'string', length: 20)]
    protected string $subtypeName;

    /**
     * @return int
     */
    public function getSubtypeId(): int
    {
        return $this->subtypeId;
    }

    /**
     * @param int $subtypeId
     */
    public function setSubtypeId(int $subtypeId): void
    {
        $this->subtypeId = $subtypeId;
    }

    /**
     * @return string
     */
    public function getSubtypeName(): string
    {
        return $this->subtypeName;
    }

    /**
     * @param string $subtypeName
     */
    public function setSubtypeName(string $subtypeName): void
    {
        $this->subtypeName = $subtypeName;
    }

}