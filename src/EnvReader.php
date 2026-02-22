<?php

namespace App;

class EnvReader
{
    public function read(): void
    {
        $rows = file(__DIR__ . '/../.env');
        foreach ($rows as $row) {
            if (!str_contains($row, '=')) {
                continue;
            }
            [$key, $value] = explode('=', trim($row));
            $_ENV[$key] = $value;
        }
    }
}
