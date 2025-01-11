<?php

require_once './app/database/connection.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        // Inicializa a conexão com a base de dados
        $this->db = Connection::getInstance();
    }

    /**
     * Obtém um utilizador pelo seu nome de utilizador (usado para login ou para verificar utilizadores existentes).
     */
    public function getUserByUsername($username)
    {
        // Consulta SQL para buscar o utilizador pelo nome de utilizador
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);  // Prepara a instrução SQL
        $stmt->execute([':username' => $username]);  // Executa a consulta com o nome de utilizador fornecido

        // Retorna o resultado como um array associativo (ou null se não houver resultados)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cria um novo utilizador (usado para o registo).
     */
    public function createUser($username, $email, $password)
    {
        // Consulta SQL para inserir um novo utilizador na base de dados
        $query = "INSERT INTO users (username, email, password, admin) VALUES (:username, :email, :password, :admin)";
        $stmt = $this->db->prepare($query);

        // Executa a inserção do novo utilizador com a palavra-passe encriptada
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),  // Encripta a palavra-passe antes de armazenar
            ':admin' => false,  // O utilizador é sempre definido como não admin por padrão
        ]);
    }

    /**
     * Autentica um utilizador (usado para login).
     */
    public function login($email, $password)
    {
        // Consulta SQL para buscar o utilizador pelo email
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);

        // Obtém os dados do utilizador da base de dados
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o utilizador existe e se a palavra-passe é correta
        if ($user && password_verify($password, $user['password'])) {
            return $user;  // Retorna os dados do utilizador se as credenciais estiverem corretas
        }

        // Retorna false se a autenticação falhar
        return false;
    }
    public function getUserById($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch();
    }

    // Update user information
    public function updateUser($userId, $username, $email, $age, $phone, $address)
    {
        $stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email, age = :age, phone = :phone, address = :address WHERE id = :id");
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':age' => $age,
            ':phone' => $phone,
            ':address' => $address,
            ':id' => $userId,
        ]);
    }
    public function updateProfileImage($userId, $imageName)
    {
        $stmt = $this->db->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :id");
        return $stmt->execute([
            ':profile_image' => $imageName,
            ':id' => $userId,
        ]);
    }
}
