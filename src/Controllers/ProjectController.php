<?php

namespace App\Controllers;

use App\Dtos\Project\ProjectFilterDto;
use App\Enums\Project\PlatformType;
use App\Enums\Project\Status;
use App\Exceptions\HttpException;
use App\Request;
use App\Services\ProjectService;
use App\Validators\ProjectValidator;
use App\Validators\ValidationMode;

class ProjectController
{
    private ProjectService $service;

    public function __construct()
    {
        $this->service = new ProjectService();
    }

    public function index(Request $request): string
    {
        $a = 1;
        $b = 0;
        $c = $a/$b;
        $status = Status::searchByValue($request->data['status'] ?? '')?->value;
        $filters = new ProjectFilterDto(
            status:$status
        );

        $projects = $this->service->findAll($filters);

        return json_encode($projects);
    }

    public function view(int $id): string
    {
        $project = $this->service->getOne($id);

        return json_encode($project);
    }

    /**
     * @throws HttpException
     */
    public function store(Request $request): string
    {
        ProjectValidator::validate($request->data, ValidationMode::CREATE);

        $project = $this->service->create($request->data);

        http_response_code(201);
        return json_encode($project);
    }

    /**
     * @throws HttpException
     */
    public function update(int $id, Request $request): string
    {
        ProjectValidator::validate($request->data, ValidationMode::UPDATE);

        $project = $this->service->update($id, $request->data);

        return json_encode($project);
    }

    public function delete(int $id): string
    {
        $this->service->delete($id);

        return json_encode(['status' => 'deleted']);
    }
}
