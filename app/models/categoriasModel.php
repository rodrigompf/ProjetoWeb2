<?php

require_once '../app/database/connection.php';

class CategoriasModel
{
    // Instância da conexão com a base de dados
    private $db;

    /**
     * Construtor que inicializa a conexão com a base de dados
     */
    public function __construct()
    {
        // Obtém a instância da conexão com a base de dados
        $this->db = Connection::getInstance();
    }

    /**
     * Obtém todas as categorias ordenadas pelo nome.
     */
    public function getAllCategorias(): array
    {
        // Prepara a consulta SQL para obter todas as categorias ordenadas pelo nome
        $stat = $this->db->prepare("SELECT * FROM categorias ORDER BY nome");

        // Executa a consulta
        $stat->execute();

        // Retorna os resultados da consulta como um array
        return $stat->fetchAll();
    }
}
