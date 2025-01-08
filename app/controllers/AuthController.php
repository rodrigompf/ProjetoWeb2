<?php

require_once './app/models/UserModel.php';

class AuthController
{
    private $userModel;

    // Construtor para inicializar o modelo de utilizador
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Método para registar um novo utilizador
    public function register()
    {
        // Verifica se o método de requisição é POST (formulário submetido)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Captura os dados enviados pelo formulário
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Tenta criar um novo utilizador
            if ($this->userModel->createUser($username, $email, $password)) {
                // Se o registo for bem-sucedido, redireciona para a página de login
                header('Location: /login');
                exit;
            } else {
                // Caso contrário, apresenta uma mensagem de erro
                echo "Erro ao registar utilizador.";
            }
        }

        // Inclui a vista para o formulário de registo
        require_once './app/views/loginRegist/registerView.php';
    }

    // Método para autenticar um utilizador
    public function login()
    {
        // Verifica se o método de requisição é POST (formulário submetido)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Captura os dados enviados pelo formulário
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Tenta autenticar o utilizador através do modelo
            $user = $this->userModel->login($email, $password);

            // Verifica se o utilizador existe e se a palavra-passe corresponde
            if ($user) {
                // Inicia uma sessão e armazena os dados do utilizador na sessão
                session_start();
                $_SESSION['user'] = $user;

                // Redireciona para a página inicial ou qualquer outra página após login bem-sucedido
                header('Location: /'); // Supondo que '/' é a rota da página inicial
                exit;
            } else {
                // Define uma mensagem de erro caso a autenticação falhe
                $error = "Email ou palavra-passe inválidos.";
            }
        }

        // Inclui a vista para o formulário de login
        require_once './app/views/loginRegist/loginView.php';
    }

    // Método para terminar a sessão (logout)
    public function logout()
    {
        // Verifica se a sessão está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Limpa e destrói a sessão atual
        session_unset();
        session_destroy();

        // Redireciona para a página inicial após o logout
        header('Location: /');
        exit();
    }
}
