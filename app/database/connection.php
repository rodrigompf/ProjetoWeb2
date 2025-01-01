<?php

class Connection
{
    // Instância única da conexão com a base de dados
    private static $instance = null;

    /**
     * Obtém a instância da conexão com a base de dados (padrão Singleton).
     */
    public static function getInstance(): ?PDO
    {
        // Verifica se a instância já existe, caso contrário cria uma nova conexão
        if (self::$instance === null) {
            try {
                // Cria a instância PDO para conectar à base de dados
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=supermarket', // Dados de conexão com o MySQL
                    'root', // Nome de utilizador
                    '', // Senha (em branco neste caso)
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Define o modo de erro para exceções
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Define o modo de obtenção dos resultados como array associativo
                    ]
                );
            } catch (PDOException $e) {
                // Em caso de erro na conexão, exibe uma mensagem de erro
                echo 'Erro na conexão com a base de dados: ' . $e->getMessage();
                return null; // Retorna null se não conseguir estabelecer a conexão
            }
        }
        // Retorna a instância existente ou a recém-criada
        return self::$instance;
    }
}
