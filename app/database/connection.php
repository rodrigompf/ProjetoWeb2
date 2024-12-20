<?php

class Connection
{
    private static $instance = null;

    public static function getInstance(): ?PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=supermarket',
                    'root',
                    '',
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                echo 'Database connection error: ' . $e->getMessage();
                return null;
            }
        }
        return self::$instance;
    }
}

?>
