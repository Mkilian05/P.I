<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $senha = $_POST["senha"] ?? '';
    $conf_senha = $_POST["confirmarSenha"] ?? '';

    if (empty($nome) || empty($email) || empty($senha)) {
        header("Location: ../views/register.php?erro=Por favor, preencha todos os campos!");
        exit;
    }

    if ($senha !== $conf_senha) {
        header("Location: ../views/register.php?erro=As senhas não coincidem!");
        exit;
    }

    $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $verifica = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $verifica->bind_param("s", $email);
        $verifica->execute();
        $verifica->store_result();

        if ($verifica->num_rows > 0) {
            header("Location: ../views/register.php?erro=Este e-mail já está cadastrado!");
            exit;
        } else {
            $stmt = $conn->prepare("INSERT INTO usuarios (nome_usuario, email, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $hash_senha);
            $stmt->execute();

            header("Location: ../views/login.php?sucesso=Conta criada com sucesso!");
            exit;
        }


    } catch (mysqli_sql_exception $e) {
        header("Location: ../views/register.php?erro=" . urlencode("Erro ao processar o cadastro: " . $e->getMessage()));
        exit;
    }

} else {
    header("Location: ../views/register.php?erro=Acesso inválido.");
    exit;
}

