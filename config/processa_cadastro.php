<?php
// Inicia a sessão para poder usar variáveis de sessão (para mensagens de erro/sucesso)
session_start();

// 1. Inclui o arquivo de conexão com o banco de dados
// Certifique-se de que o caminho para o arquivo 'conexao.php' está correto.
require_once '../config/database.php'; // Usando o arquivo que criamos anteriormente

// 2. Verifica se o formulário foi enviado (se o método é POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Coleta e limpa os dados do formulário
    $nome_usuario = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $confirmarSenha = trim($_POST['confirmarSenha']);

    // 4. Validações
    // a) Verifica se as senhas são iguais
    if ($senha !== $confirmarSenha) {
        $_SESSION['error_message'] = "As senhas não coincidem!";
        header("Location: ../views/register.php");
        exit();
    }

    // b) Verifica se o email já existe no banco de dados
    try {
        $sql_check = "SELECT id_usuario FROM usuarios WHERE email = :email";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            $_SESSION['error_message'] = "Este e-mail já está cadastrado!";
            header("Location: ../views/register.php");
            exit();
        }

    } catch (PDOException $e) {
        // Em caso de erro na verificação, redireciona com uma mensagem genérica
        $_SESSION['error_message'] = "Ocorreu um erro. Tente novamente.";
        header("Location: ../views/register.php");
        exit();
    }

    // 5. Segurança: Criptografa a senha
    // NUNCA armazene senhas em texto plano!
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // 6. Insere o novo usuário no banco de dados usando Prepared Statements
    try {
        $sql_insert = "INSERT INTO usuarios (nome_usuario, email, senha) VALUES (:nome, :email, :senha)";
        $stmt_insert = $pdo->prepare($sql_insert);

        // Associa os parâmetros
        $stmt_insert->bindParam(':nome', $nome_usuario, PDO::PARAM_STR);
        $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt_insert->bindParam(':senha', $senha_hash, PDO::PARAM_STR);

        // Executa a query
        if ($stmt_insert->execute()) {
            // Sucesso! Redireciona para a página de login com uma mensagem
            $_SESSION['success_message'] = "Cadastro realizado com sucesso! Faça o login.";
            header("Location: ../views/login.php"); // Altere para o caminho da sua página de login
            exit();
        }

    } catch (PDOException $e) {
        // Em caso de erro na inserção, redireciona com uma mensagem de erro
        // Em produção, você poderia logar o erro: error_log($e->getMessage());
        $_SESSION['error_message'] = "Erro ao cadastrar. Tente novamente.";
        header("Location: ../views/register.php");
        exit();
    }

} else {
    // Se alguém tentar acessar o script diretamente, redireciona
    header("Location: ../views/register.php");
    exit();
}
?>