<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

readonly class Product
{
    public function __construct(
        private int $id,
        private string $uuid,
        private bool $isActive,
        private Category $category,
        private string $name,
        private string $description,
        private string $thumbnail,
        private float $price,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
