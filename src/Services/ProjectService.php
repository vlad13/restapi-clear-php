<?php

namespace App\Services;

use App\Dtos\Project\CreateProjectDto;
use App\Dtos\Project\ProjectFilterDto;
use App\Dtos\Project\UpdateProjectDto;
use App\Enums\Project\PlatformType;
use App\Enums\Project\Status;
use App\Models\Project;
use App\Repositories\ProjectRepository;

class ProjectService
{
    private ProjectRepository $repository;

    public function __construct()
    {
        $this->repository = new ProjectRepository();
    }

    public function findAll(ProjectFilterDto $filters): array
    {
        return $this->repository->findAll($filters);
    }

    public function getOne(int $id): Project
    {
        return $this->repository->findById($id);
    }

    public function create(array $data): Project
    {
        $platformType = PlatformType::searchByValue($data['platformType'] ?? '')?->value;
        $status = Status::searchByValue($data['status'] ?? '')?->value;

        $dto = new CreateProjectDto(
            trim($data['name'] ?? ''),
            trim($data['url'] ?? ''),
            $platformType,
            $status,
            trim($data['description'] ?? ''),
            date('Y-m-d H:i:s'),
            ''
        );

        return $this->repository->create($dto);
    }

    public function update(int $id, array $data): Project
    {
        if (isset($data['platformType'])) {
            $data['platformType'] = PlatformType::searchByValue($data['platformType'])?->value;
        }
        if (isset($data['status'])) {
            $data['status'] = Status::searchByValue($data['status'])?->value;
        }
        $data['updatedAt'] = date('Y-m-d H:i:s');
        $dto = new UpdateProjectDto($data);

        return $this->repository->update($id, $dto);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}
