<?php

namespace App\Validators;

use App\Enums\Project\PlatformType;
use App\Enums\Project\Status;
use App\Exceptions\HttpException;

class ProjectValidator
{
    /**
     * @throws HttpException
     */
    public static function validate(array $data, ValidationMode $mode): void
    {
        self::validateName($data, $mode);
        self::validateUrl($data, $mode);
        self::validatePlatformType($data, $mode);
        self::validateStatus($data, $mode);
    }

    /**
     * @throws HttpException
     */
    private static function validateName(array $data, ValidationMode $mode): void
    {
        if ($mode === ValidationMode::CREATE && empty($data['name'])) {
            throw new HttpException('Name is required');
        }

        if (isset($data['name']) && mb_strlen($data['name']) > 255) {
            throw new HttpException('The Name field cannot be more than 255 characters');
        }
    }

    /**
     * @throws HttpException
     */
    private static function validateUrl(array $data, ValidationMode $mode): void
    {
        if ($mode === ValidationMode::CREATE && empty($data['url'])) {
            throw new HttpException('Url is required');
        }

        if (isset($data['url']) && mb_strlen($data['url']) > 255) {
            throw new HttpException('The Url field cannot be more than 255 characters');
        }

        if (isset($data['url']) && !self::isValidProjectUrl($data['url'])) {
            throw new HttpException('The Url field is not valid');
        }
    }

    /**
     * @throws HttpException
     */
    private static function validatePlatformType(array $data, ValidationMode $mode): void
    {
        if ($mode === ValidationMode::CREATE && empty($data['platformType'])) {
            throw new HttpException('platformType is required');
        }

        if (isset($data['platformType']) && !in_array($data['platformType'], PlatformType::names(), true)) {
            throw new HttpException('Incorrect value for the platformType field');
        }
    }

    /**
     * @throws HttpException
     */
    private static function validateStatus(array $data, ValidationMode $mode): void
    {
        if ($mode === ValidationMode::CREATE && empty($data['status'])) {
            throw new HttpException('status is required');
        }

        if (isset($data['status']) && !in_array($data['status'], Status::names(), true)) {
            throw new HttpException('Incorrect value for the status field');
        }
    }

    private static function isValidProjectUrl(string $url): bool
    {
        $parts = parse_url($url);

        if ($parts === false) {
            return false;
        }

        if (!isset($parts['scheme'], $parts['host'])) {
            return false;
        }

        return in_array($parts['scheme'], ['http', 'https'], true);
    }
}
