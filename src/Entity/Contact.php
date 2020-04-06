<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"contact:show"})
     * @SWG\Property(description="The unique identifier of the contact.")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"contact:show"})
     * @SWG\Property(description="The name of the contact.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups({"contact:show"})
     * @SWG\Property(description="contact email.")
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Groups({"contact:show"})
     * @SWG\Property(description="The subject of the contact.")
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     * @Groups({"contact:show"})
     * @SWG\Property(description="The body of the contact.")
     */
    private $body;

    /**
     * @var DateTimeInterface
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({"contact:show"})
     * @SWG\Property(description="Creating data.")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
