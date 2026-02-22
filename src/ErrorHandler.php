<?php
namespace App;

use Throwable;
use ErrorException;

class ErrorHandler
{
    /**
     * @throws ErrorException
     */
    public function register(): void
    {
        $this->registerPhpSettings();
        $this->registerErrorHandler();
        $this->registerExceptionHandler();
        $this->registerShutdownHandler();
    }

    private function registerPhpSettings(): void
    {
        ini_set('display_errors', '0');
        error_reporting(E_ALL);
    }

    private function registerErrorHandler(): void
    {
        set_error_handler(function ($severity, $message, $file, $line) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });
    }

    private function registerExceptionHandler(): void
    {
        set_exception_handler(function (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            error_log("\n[" . date('Y-m-d H:i:s') . "]\n" . $e . "\n", 3, $_ENV['LOG_FILE']);
        });
    }

    /*
     * Перехватывает ошибки которые останавливают php немедленно.
     * Т.е. возникает критическая ошибка, и php сам вызовет функцию записанную в register_shutdown_function
     */
    private function registerShutdownHandler(): void
    {
        register_shutdown_function(function () {
            $error = error_get_last();

            if ($error && in_array($error['type'], [
                    E_ERROR,
                    E_PARSE,
                    E_CORE_ERROR,
                    E_COMPILE_ERROR,
                ], true)) {
                http_response_code(500);
                echo json_encode([
                    'error' => $error
                ]);
                error_log("\n[" . date('Y-m-d H:i:s') . "]\n" . json_encode($error) . "\n", 3, $_ENV['LOG_FILE']);
            }
        });
    }
}
