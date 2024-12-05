<?php

// Este ficheiro vai servir para criar uma conexão à base de dados

class Connection
{
    private static $instance = null;

    public static function getInstance(): ?PDO
    {
        if (self::$instance === null) {
            try {
                // Use the 'login' database instead of 'supermarket'
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=supermarket',  // Change this line
                    'root',
                    '',     
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                return null;
            }
        }
        return self::$instance;
    }
}


?>
