<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubscriberRepository")
 * @Table(name="subscriber",indexes={@Index(name="status", columns={"status"})})
 */
class Subscriber
{
    public const STATUS_BANNED = 'banned';
    public const STATUS_UNVERIFIED = 'unverified';
    public const STATUS_APPROVED = 'approved';

    public static $statusNames = [
        self::STATUS_BANNED => 'Banned',
        self::STATUS_UNVERIFIED => 'Unverified',
        self::STATUS_APPROVED => 'Approved'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTimeInterface
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(): self
    {
        $this->token = Uuid::uuid4();

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
