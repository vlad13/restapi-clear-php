<?php

namespace App\Repositories;

use App\Database\Connection;
use App\Dtos\Project\CreateProjectDto;
use App\Dtos\Project\ProjectFilterDto;
use App\Dtos\Project\UpdateProjectDto;
use App\Enums\Project\PlatformType;
use App\Enums\Project\Status;
use App\Models\Project;
use PDO;

class ProjectRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::get();
    }

    public function findAll(ProjectFilterDto $filters): array
    {
        $sql = "SELECT * FROM projects";
        $params = [];

        $sqlWhere = [];
        if ($filters->status !== null) {
            $sqlWhere[] = "status = ?";
            $params[] = $filters->status;
        }

        if ($sqlWhere) {
            $sql .= " WHERE " . implode(" AND ", $sqlWhere);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $projects = [];
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($rows) {
            foreach($rows as $row) {
                $projects[] = Project::fromArray($row);
            }
        }

        return $projects;
    }

    public function create(CreateProjectDto $dto): Project
    {
        $sql = "INSERT INTO projects (name, url, platform_type, status, description, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $dto->name,
            $dto->url,
            $dto->platformType,
            $dto->status,
            $dto->description,
            $dto->createdAt,
        ]);
        $id = $this->db->lastInsertId();

        return new Project(
            $id,
            $dto->name,
            $dto->url,
            PlatformType::tryFrom($dto->platformType)->name,
            Status::tryFrom($dto->status)->name,
            $dto->description,
            $dto->createdAt,
            ''
        );
    }

    public function update(int $id, UpdateProjectDto $dto): Project
    {
        // Сопоставление свойств DTO с колонками БД
        $columnsMap = [
            'name' => 'name',
            'url' => 'url',
            'platformType' => 'platform_type',
            'status' => 'status',
            'description' => 'description',
            'updatedAt' => 'updated_at',
        ];

        $sqlItems = [];
        $sqlParams = [];

        // Формируем SET для UPDATE
        foreach ($columnsMap as $prop => $column) {
            if (isset($dto->$prop)) {
                $sqlItems[] = "$column = ?";
                $sqlParams[] = $dto->$prop;
            }
        }

        // Если нет полей для обновления, просто возвращаем существующий объект
        if (empty($sqlItems)) {
            return $this->findById($id);
        }

        $sqlParams[] = $id;
        $sql = "UPDATE projects SET " . implode(', ', $sqlItems) . " WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($sqlParams);

        return $this->findById($id);
    }

    public function findById(int $id): Project
    {
        $stmt = $this->db->prepare(
            "SELECT id, name, url, platform_type, status, description, created_at, updated_at 
                    FROM projects
                    WHERE id = ?"
        );
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new \RuntimeException("Project with id $id not found");
        }

        return new Project(
            id: (int)$row['id'],
            name: $row['name'],
            url: $row['url'],
            platformType: PlatformType::tryFrom($row['platform_type'])->name,
            status: Status::tryFrom($row['status'])->name,
            description: $row['description'],
            createdAt: $row['created_at'],
            updatedAt: $row['updated_at'],
        );
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }
}
