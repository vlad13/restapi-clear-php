<?php

namespace App\Dtos\Project;
class CreateProjectDto {
    public function __construct(
        public ?string $name,
        public ?string $url,
        public ?int $platformType,
        public ?int $status,
        public ?string $description,
        public ?string $createdAt
    ) {

    }
}
