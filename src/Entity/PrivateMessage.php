<?php

namespace App\Entity;

use App\Repository\PrivateMessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrivateMessageRepository::class)
 */
class PrivateMessage
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
    private $content;

    /**
     * @ORM\OneToOne(targetEntity=AppUser::class, inversedBy="privateMessage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\OneToOne(targetEntity=AppUser::class, inversedBy="privateMessage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $reciver;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSender(): ?AppUser
    {
        return $this->sender;
    }

    public function setSender(AppUser $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReciver(): ?AppUser
    {
        return $this->reciver;
    }

    public function setReciver(AppUser $reciver): self
    {
        $this->reciver = $reciver;

        return $this;
    }

    public function getSendTime(): ?\DateTimeInterface
    {
        return $this->sendTime;
    }

    public function setSendTime(\DateTimeInterface $sendTime): self
    {
        $this->sendTime = $sendTime;

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
}
