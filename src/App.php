<?php
namespace App;

use PDO;

class App {

    public static $pdo;

    public static $auth;

    public static function getPDO(): PDO
    {
        if (!self::$pdo) {
            self::$pdo = Database::connect();
        }
        return self::$pdo;
    }

    public static function getAuth(): Auth
    {
        if (!self::$auth) {
            self::$auth = new Auth(self::getPDO(), '/login.php');
        }
        return self::$auth;
    }

}