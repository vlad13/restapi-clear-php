<?php

namespace App\Models;

use App\Enums\Project\PlatformType;
use App\Enums\Project\Status;

final class Project
{
    public function __construct(
        public readonly int $id,
        public ?string $name,
        public ?string $url,
        public ?string $platformType,
        public ?string $status,
        public ?string $description,
        public ?string $createdAt,
        public ?string $updatedAt
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['id'],
            name: $data['name'] ?? null,
            url: $data['url'] ?? null,
            platformType: isset($data['platform_type']) ? PlatformType::tryFrom($data['platform_type'])->name : null,
            status: isset($data['status']) ? Status::tryFrom($data['status'])->name : null,
            description: $data['description'] ?? null,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
        );
    }
}
