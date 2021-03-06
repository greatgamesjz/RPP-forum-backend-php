<?php

namespace App\Entity;

use App\Repository\PrivateMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrivateMessageRepository::class)
 */
class PrivateMessage implements \JsonSerializable
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
     * @ORM\Column(type="datetime")
     */
    private $sendTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class, inversedBy="recivedMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reciver;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class, inversedBy="sentMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    public function __construct()
    {
        $this->isDeleted = false;
        $this->sendTime = new \DateTime();
    }

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

    public function getReciver(): ?AppUser
    {
        return $this->reciver;
    }

    public function setReciver(?AppUser $reciver): self
    {
        $this->reciver = $reciver;

        return $this;
    }

    public function getSender(): ?AppUser
    {
        return $this->sender;
    }

    public function setSender(?AppUser $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "Id" => $this->getId(),
            "Content" => $this->getContent(),
            "SendTime" => $this->getSendTime(),
            "IsDeleted" => $this->getIsDeleted(),
            "Reciver" => $this->getReciver()->getid(),
            "Sender" => $this->getSender()->getid(),
        ];
    }
}
