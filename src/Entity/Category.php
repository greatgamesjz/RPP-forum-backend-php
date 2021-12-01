<?php

namespace App\Entity;

use App\Controller\CategoryController;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categoryName;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\OneToMany(targetEntity=Topic::class, mappedBy="category")
     */
    private $topics;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    public function __construct()
    {
        $this->creation_date = new \DateTime();
        $this->topics = new ArrayCollection();
        $this->isDeleted = false;
        $this->isActive = true;
        $this->creation_date = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    public function getCreator(): ?AppUser
    {
        return $this->creator;
    }

    public function setCreator(?AppUser $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics[] = $topic;
            $topic->setCategory($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getCategory() === $this) {
                $topic->setCategory(null);
            }
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function jsonSerialize()
    {
        $topicList = $this->getTopics();
        $topicListId = [];
        foreach($topicList as $topic)
        {
            $topicListId[] = $topic->getId();
        }
        return [
            "Id" => $this->getId(),
            "CategoryName" => $this->getCategoryName(),
            "CreatorName" => $this->getCreator()->getNickname(),
            "CreatorId" => $this->getCreator()->getId(),
            "CreationDate" => $this->getCreationDate(),
            "IsActive" => $this->getIsActive(),
            "IsDeleted" => $this->getIsDeleted(),
            "TopicsId" => $topicListId
        ];
    }
}
