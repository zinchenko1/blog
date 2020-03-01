<?php

namespace App\Entity;

use App\Entity\User\Commentator;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"comment:show"})
     * @SWG\Property(description="The unique identifier of the comment.")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"comment:show"})
     * @SWG\Property(description="The title of the comment.")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"comment:show"})
     * @SWG\Property(description="The body of the comment.")
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     */
    private $post;


    /**
     * @var DateTimeInterface
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({"comment:show"})
     * @SWG\Property(description="Creating data.")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Commentator", inversedBy="comments")
     * @Groups({"comment:show"})
     * @SWG\Property(description="The author of the comment.")
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

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

    public function getAuthor(): ?Commentator
    {
        return $this->author;
    }

    public function setAuthor(?Commentator $author): self
    {
        $this->author = $author;

        return $this;
    }
}
