<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    public const MAIN = 1;
    public const BASIC = 0;

    public const STATUSES = [
        'Main' => self::MAIN,
        'Basic' => self::BASIC,
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"post:show", "category:show"})
     * @SWG\Property(description="The unique identifier of the category.")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post:show", "category:show"})
     * @SWG\Property(description="Category name.")
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @Groups({"category:show"})
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(description="Category slug.")
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="category", cascade={"persist", "remove"})
     * @Groups({"category:show"})
     * @SWG\Property(ref=@Model(type=Post::class))
     */
    private $posts;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     * @Groups({"category:show"})
     * @SWG\Property(description="Is main category.")
     */
    private $isMain;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->title;
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        $this->posts[] = $post;

        return $this;
    }

    public function removePost(Post $post): void
    {
        $this->posts->removeElement($post);
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

    public function getIsMain()
    {
        return $this->isMain;
    }

    public function setIsMain($isMain): self
    {
        $this->isMain = $isMain;

        return $this;
    }
}
