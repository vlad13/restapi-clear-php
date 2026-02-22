<?php
namespace App\Dtos\Project;

class UpdateProjectDto
{
    public ?string $name = null;
    public ?string $url = null;
    public ?int $platformType = null;
    public ?int $status = null;
    public ?string $description = null;

    public string $updatedAt;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
