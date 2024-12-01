<?php

require_once '../app/database/connection.php';

class CategoriasModel
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getAllCategorias(): array
    {
        $stat = $this->db->prepare("SELECT * FROM categorias ORDER BY nome");
        $stat->execute();
        return $stat->fetchAll();
    }
}
