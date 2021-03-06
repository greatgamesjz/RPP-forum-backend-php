<?php

namespace App\Entity;

use App\Repository\AppUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=AppUserRepository::class)
 * @ORM\Table(name="`appuser`")
 */
class AppUser implements PasswordAuthenticatedUserInterface{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="creator")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Topic::class, mappedBy="creator")
     */
    private $topics;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="creator")
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="usersLiked")
     */
    private $likes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatarFileName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBanned;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;
  
    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDate;

    /**
     * @ORM\OneToMany(targetEntity=PrivateMessage::class, mappedBy="reciver")
     */
    private $recivedMessages;

    /**
     * @ORM\OneToMany(targetEntity=PrivateMessage::class, mappedBy="sender")
     */
    private $sentMessages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenExpireDate;

    /**
     * @ORM\OneToMany(targetEntity=Status::class, mappedBy="user")
     */
    private $statuses;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activationToken;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->isDeleted = false;
        $this->isActive = false;
        $this->registrationDate = new \DateTime();
        $this->avatarFileName = "";
        $this->isBanned = false;
        $this->recivedMessages = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->statuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addUser($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->removeElement($role)) {
            $role->removeUser($this);
        }
        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setCreator($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCreator() === $this) {
                $category->setCreator(null);
            }
        }

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
            $topic->setCreator($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getCreator() === $this) {
                $topic->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCreator($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCreator() === $this) {
                $post->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Post $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->addUsersLiked($this);
        }

        return $this;
    }

    public function removeLike(Post $like): self
    {
        if ($this->likes->removeElement($like)) {
            $like->removeUsersLiked($this);
        }

        return $this;
    }

    public function getAvatarFileName(): ?string
    {
        return $this->avatarFileName;
    }

    public function setAvatarFileName(string $avatarFileName): self
    {
        $this->avatarFileName = $avatarFileName;

        return $this;
    }

    public function getIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

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
  
    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * @return Collection|PrivateMessage[]
     */
    public function getRecivedMessages(): Collection
    {
        return $this->recivedMessages;
    }

    public function addRecivedMessage(PrivateMessage $recivedMessage): self
    {
        if (!$this->recivedMessages->contains($recivedMessage)) {
            $this->recivedMessages[] = $recivedMessage;
            $recivedMessage->setReciver($this);
        }

        return $this;
    }

    public function removeRecivedMessage(PrivateMessage $recivedMessage): self
    {
        if ($this->recivedMessages->removeElement($recivedMessage)) {
            // set the owning side to null (unless already changed)
            if ($recivedMessage->getReciver() === $this) {
                $recivedMessage->setReciver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PrivateMessage[]
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(PrivateMessage $sentMessage): self
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages[] = $sentMessage;
            $sentMessage->setSender($this);
        }

        return $this;
    }

    public function removeSentMessage(PrivateMessage $sentMessage): self
    {
        if ($this->sentMessages->removeElement($sentMessage)) {
            // set the owning side to null (unless already changed)
            if ($sentMessage->getSender() === $this) {
                $sentMessage->setSender(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getTokenExpireDate(): ?\DateTimeInterface
    {
        return $this->tokenExpireDate;
    }

    public function setTokenExpireDate(?\DateTimeInterface $tokenExpireDate): self
    {
        $this->tokenExpireDate = $tokenExpireDate;

        return $this;
    }

    /**
     * @return Collection|Status[]
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function addStatus(Status $status): self
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses[] = $status;
            $status->setUser($this);
        }

        return $this;
    }

    public function removeStatus(Status $status): self
    {
        if ($this->statuses->removeElement($status)) {
            // set the owning side to null (unless already changed)
            if ($status->getUser() === $this) {
                $status->setUser(null);
            }
        }

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    public function setActivationToken(?string $activationToken): self
    {
        $this->activationToken = $activationToken;

        return $this;
    }

}
