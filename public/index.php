<?php

use App\EnvReader;
use App\ErrorHandler;
use App\Router;
use App\Request;

// Подключаем файлы композера
require __DIR__ . '/../vendor/autoload.php';

// Загружаем параметры из .env файла в $_ENV
(new EnvReader())->read();

// Создаем $request автоматически с параметрами запроса
$request = new Request();

// Подключаем роуты
$router = new Router();
require __DIR__ . '/../config/routes.php';

// Перехватываем все ошибки, исключения чтобы вывести в ответе
(new ErrorHandler())->register();

// Выполняем запрос, т.е. вызываем экшен контроллера
try {
    //error_log("test"); exit;
    echo $router->executeRequest($request);
} catch (App\Exceptions\HttpException $e) {
    http_response_code((int)$e->getCode());
    echo json_encode(['error' => $e->getMessage()]);
    error_log("\n[" . date('Y-m-d H:i:s') . "]\n" . $e . "\n", 3, $_ENV['LOG_FILE']);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    error_log("\n[" . date('Y-m-d H:i:s') . "]\n" . $e . "\n", 3, $_ENV['LOG_FILE']);
}
