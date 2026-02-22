<?php

namespace App;

class Request
{
    public string $method;
    public string $uri;
    public array|null $data;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->data = $_GET;

        $input = file_get_contents('php://input');

        if (!empty($input)) {
            if (str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
                $body = json_decode($input, true) ?? [];
            } else {
                parse_str($input, $body);
            }

            $this->data = array_merge($this->data, $body);
        }
    }
}
