<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Enums\HttpResponseStatus;
use App\Repository\ReadingRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Exception;

#[ORM\Entity(repositoryClass: ReadingRepository::class)]
class NotificationReading
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Type(type: 'int')]
    private ?int $id = null;

    #[ORM\Column(enumType: HttpResponseStatus::class)]
    #[Assert\Type(type: HttpResponseStatus::class)]
    private HttpResponseStatus $status;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'array')]
    private array $body;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: DateTime::class)]
    private DateTime $readAt;

    #[ORM\ManyToOne(inversedBy: 'readings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Notification $notification = null;

    public function __construct(
        HttpResponseStatus $status,
        DateTime $readAt,
        array $body = [],
    ) {
        $this->status = $status;
        $this->readAt = $readAt;
        $this->body = $body;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status->value;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getReadAt(): ?DateTime
    {
        return $this->readAt;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function getNotificationId(): ?int
    {
        if ($this->notification) {
            return $this->notification->getId();
        }

        throw new Exception('Missed notification.');
    }

    public function isFailed(): bool
    {
        return $this->status->isFailed();
    }
}
