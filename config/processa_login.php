<?php
// Inicia a sessão. É fundamental que seja a primeira coisa no arquivo.
session_start();

// Inclui o arquivo de conexão com o banco de dados.
require_once "database.php";

// Verifica se o formulário foi enviado (método POST).
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Pega o email e a senha do formulário, removendo espaços em branco.
    $email = trim($_POST["email"] ?? '');
    $senha = $_POST["senha"] ?? '';

    // Verifica se os campos não estão vazios.
    if (empty($email) || empty($senha)) {
        // Se estiverem vazios, define uma mensagem de erro na sessão e redireciona de volta para o login.
        $_SESSION['erro_login'] = "Por favor, preencha o e-mail e a senha.";
        header("Location: ../views/login.php");
        exit;
    }

    try {
        // Prepara a consulta SQL para buscar o usuário pelo e-mail.
        $stmt = $conn->prepare("SELECT id_usuario, nome_usuario, senha FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se encontrou um usuário.
        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            // Verifica se a senha fornecida corresponde à senha hash no banco de dados.
            if (password_verify($senha, $usuario['senha'])) {
                // Senha correta!

                // Limpa sessões de erro antigas.
                unset($_SESSION['erro_login']);

                // Armazena os dados do usuário na sessão.
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nome'] = $usuario['nome_usuario'];

                // Redireciona para o painel administrativo.
                header("Location: ../views/admin/dashboard_admin.php?sucesso=Login realizado com sucesso!");
                exit;

            } else {
                // Senha incorreta.
                $_SESSION['erro_login'] = "E-mail ou senha inválidos.";
                header("Location: ../views/login.php");
                exit;
            }
        } else {
            // Nenhum usuário encontrado com o e-mail.
            $_SESSION['erro_login'] = "E-mail ou senha inválidos.";
            header("Location: ../views/login.php");
            exit;
        }

    } catch (mysqli_sql_exception $e) {
        // Em caso de erro com o banco de dados.
        $_SESSION['erro_login'] = "Ocorreu um erro. Tente novamente mais tarde.";
        // Para depuração, você pode querer registrar o erro: error_log($e->getMessage());
        header("Location: ../views/login.php");
        exit;
    }

} else {
    // Se alguém tentar acessar o arquivo diretamente, redireciona para o login.
    header("Location: ../views/login.php");
    exit;
}