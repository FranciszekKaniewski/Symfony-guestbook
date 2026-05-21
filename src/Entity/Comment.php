<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Imię jest wymagane')]
    #[Assert\Regex(
        pattern: '/(admin|root)/i',
        match: false,
        message: 'Imię nie może zawierać słów "admin" ani "root".'
    )]
    private ?string $author = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 10, minMessage: 'Komentarz musi mieć co najmniej 10 znaków')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[Assert\NotBlank]
    #[Assert\Email(message: 'Podany adres nie jest prawidłowy')]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null; 

    // DODANE: Właściwość updatedAt
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conference $conference = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message: 'To nie jest poprawny adres URL', requireTld: true)]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoFilename = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // DODANE: Getter dla updatedAt
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // DODANE: Setter dla updatedAt
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getConference(): ?Conference
    {
        return $this->conference;
    }

    public function setConference(?Conference $conference): static
    {
        $this->conference = $conference;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getPhotoFilename(): ?string
    {
        return $this->photoFilename;
    }

    public function setPhotoFilename(?string $photoFilename): static
    {
        $this->photoFilename = $photoFilename;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        // Ustawia datę tylko jeśli jej jeszcze nie ma
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    } 

    // POPRAWIONE: Zmiana nazwy metody, by nie konfliktowała z setterem
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void 
    {
        $this->updatedAt = new \DateTimeImmutable();
    } 
}