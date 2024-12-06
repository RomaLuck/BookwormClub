<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $publicationDate = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    public function __construct()
    {
        $this->setPublicationDate();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
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

    public function getPublicationDate(): ?\DateTimeImmutable
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(): static
    {
        $this->publicationDate = new \DateTimeImmutable;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'rating' => $this->getRating(),
            'body' => $this->getBody(),
            'author' => $this->getAuthor(),
            'publicationDate' => $this->getPublicationDate()->format('Y-m-d'),
        ];
    }
}
