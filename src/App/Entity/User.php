<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    protected int $uid;

    #[ORM\Column(type: 'string', unique: true)]
    protected string $email;
    
    #[ORM\Column(type: 'string', length: 1000)]
    protected string $password;

    #[ORM\Column(type: 'string')]
    protected string $lastName;

    #[ORM\Column(type: 'string')]
    protected string $firstName;

    #[ORM\Column(type: 'boolean')]
    protected bool $admin;

    #[ORM\Column(type: 'integer')]
    protected int $nbStreet;

    #[ORM\Column(type: 'string', length: 50)]
    protected string $street;

    #[ORM\Column(type: 'string', length: 50)]
    protected string $city;

    #[ORM\Column(type: 'integer')]
    protected int $postcode;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $birthDate;


    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * @param bool $admin
     */
    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    /**
     * @return int
     */
    public function getNbStreet(): int
    {
        return $this->nbStreet;
    }

    /**
     * @param int $nbStreet
     */
    public function setNbStreet(int $nbStreet): void
    {
        $this->nbStreet = $nbStreet;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return int
     */
    public function getPostcode(): int
    {
        return $this->postcode;
    }

    /**
     * @param int $postcode
     */
    public function setPostcode(int $postcode): void
    {
        $this->postcode = $postcode;
    }

    /**
     * @return DateTime
     */
    public function getBirthDate(): DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param DateTime $birthDate
     */
    public function setBirthDate(DateTime $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return String
     */
    public function getBirthDateToString(): String
    {
        $date = $this->birthDate->format('Y-m-d');
        return $date;
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

}
