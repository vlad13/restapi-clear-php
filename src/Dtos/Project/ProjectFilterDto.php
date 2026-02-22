<?php

namespace App\Dtos\Project;
class ProjectFilterDto
{
    public function __construct(
        public ?int $status = null,
    ) {}
}
