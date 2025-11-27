<?php
// config/processa_login.php

session_start();
require_once "database.php"; // Certifique-se que este arquivo está na mesma pasta

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? '');
    $senha = $_POST["senha"] ?? '';

    if (empty($email) || empty($senha)) {
        $_SESSION['erro_login'] = "Preencha e-mail e senha.";
        header("Location: ../views/login.php"); // Verifique se o caminho está certo
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT id_usuario, nome_usuario, senha FROM usuarios WHERE email = ? AND (is_deleted IS NULL OR is_deleted = 0)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            // --- VERIFICAÇÃO DE SENHA ---
            // Verifica se é hash (password_verify) OU se é texto puro (para seus testes manuais funcionarem)
            $senhaValida = false;
            
            if (password_verify($senha, $usuario['senha'])) {
                $senhaValida = true;
            } elseif ($senha === $usuario['senha']) {
                // Removemos isso depois, mas ajuda se vc cadastrou "123" direto no banco
                $senhaValida = true; 
            }

            if ($senhaValida) {
                // --- CORREÇÃO DO NOME DA SESSÃO ---
                // Agora usamos 'id_usuario' para bater com o dashboard
                $_SESSION['id_usuario']   = $usuario['id_usuario'];
                $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
                $_SESSION['email']        = $email;

                unset($_SESSION['erro_login']);

                // Redireciona para o Dashboard
                header("Location: ../views/dashboard.php");
                exit;

            } else {
                $_SESSION['erro_login'] = "Senha incorreta.";
                header("Location: ../views/login.php");
                exit;
            }
        } else {
            $_SESSION['erro_login'] = "E-mail não encontrado.";
            header("Location: ../views/login.php");
            exit;
        }

    } catch (Exception $e) {
        $_SESSION['erro_login'] = "Erro no sistema: " . $e->getMessage();
        header("Location: ../views/login.php");
        exit;
    }

} else {
    header("Location: ../views/login.php");
    exit;
}
?>