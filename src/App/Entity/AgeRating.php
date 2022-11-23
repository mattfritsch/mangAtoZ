<?php

namespace App\Entity;

use App\Repository\AgeRatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgeRatingRepository::class)]
#[ORM\Table(name: 'age_rating')]
class AgeRating
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $ageId;

    #[ORM\Column(type: 'string', length: 20)]
    protected string $ageName;

    /**
     * @return int
     */
    public function getAgeId(): int
    {
        return $this->ageId;
    }

    /**
     * @param int $ageId
     */
    public function setAgeId(int $ageId): void
    {
        $this->ageId = $ageId;
    }

    /**
     * @return string
     */
    public function getAgeName(): string
    {
        return $this->ageName;
    }

    /**
     * @param string $ageName
     */
    public function setAgeName(string $ageName): void
    {
        $this->ageName = $ageName;
    }

}