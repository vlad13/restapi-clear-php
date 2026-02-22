<?php

namespace App\Database;

use PDO;

class Connection
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../../config/database.php';

            self::$pdo = new PDO(
                "mysql:host=".$config['host'].";dbname=".$config['database'].";charset=utf8" ,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
        }

        return self::$pdo;
    }
}
