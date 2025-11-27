<?php
// config/processa_dispositivos.php

session_start();
require_once __DIR__ . '/../config/database.php';

// Proteção de sessão
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $acao = $_POST['acao'] ?? 'cadastrar'; 
    $id_usuario = $_SESSION['id_usuario'];

    try {
        // --- GUARDIÕES DE SEGURANÇA ---

        // 1. Verifica se o AMBIENTE alvo pertence ao usuário (Para Cadastrar ou Mover)
        function verificarDonoAmbiente($conn, $id_ambiente, $id_usuario) {
            $sql = "SELECT a.id_ambiente 
                    FROM ambiente a 
                    JOIN casa c ON a.fk_casa_id_casa = c.id_casa 
                    WHERE a.id_ambiente = ? AND c.fk_usuario_id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_ambiente, $id_usuario);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) {
                throw new Exception("Acesso negado: O ambiente selecionado não pertence a você.");
            }
        }

        // 2. Verifica se o DISPOSITIVO alvo pertence ao usuário (Para Editar ou Excluir)
        function verificarDonoDispositivo($conn, $id_dispositivo, $id_usuario) {
            // Caminho: Dispositivo -> Ambiente_Dispositivo -> Ambiente -> Casa -> Usuário
            $sql = "SELECT d.id_dispositivo 
                    FROM dispositivos d
                    JOIN ambiente_dispositivo ad ON d.id_dispositivo = ad.fk_disp_id_disp
                    JOIN ambiente a ON ad.fk_ambi_id_ambi = a.id_ambiente
                    JOIN casa c ON a.fk_casa_id_casa = c.id_casa
                    WHERE d.id_dispositivo = ? AND c.fk_usuario_id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_dispositivo, $id_usuario);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) {
                throw new Exception("Acesso negado: Este dispositivo não pertence a você.");
            }
        }

        // ====================================================
        // AÇÃO: EXCLUIR
        // ====================================================
        if ($acao === 'excluir') {
            $id = $_POST['id_dispositivo'];
            if (empty($id) || !is_numeric($id)) throw new Exception("ID inválido.");

            // SEGURANÇA: Verifica dono antes de excluir
            verificarDonoDispositivo($conn, $id, $id_usuario);

            $sql = "UPDATE dispositivos SET is_deleted = 1, deleted_at = NOW() WHERE id_dispositivo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()) throw new Exception("Erro ao excluir: " . $stmt->error);
            
            $_SESSION['sucesso'] = "Dispositivo excluído!";
            header("Location: ../views/dispositivos.php");
            exit;
        }

        // ====================================================
        // DADOS COMUNS
        // ====================================================
        $nome       = $_POST['dispositivoNome'];
        $potencia   = $_POST['dispositivoPotencia'];
        $id_cat     = $_POST['dispositivoTipo'];
        $id_amb     = $_POST['dispositivoAmbiente'];
        $quantidade = $_POST['dispositivoQuantidade'];

        if (empty($nome) || empty($potencia) || empty($id_cat) || empty($id_amb) || empty($quantidade)) {
            throw new Exception("Todos os campos são obrigatórios!");
        }

        // SEGURANÇA: Verifica se o ambiente de destino é do usuário (impede injeção de ID de ambiente alheio)
        verificarDonoAmbiente($conn, $id_amb, $id_usuario);

        $conn->begin_transaction();

        // ====================================================
        // AÇÃO: EDITAR
        // ====================================================
        if ($acao === 'editar') {
            $id = $_POST['id_dispositivo'];

            // SEGURANÇA: Verifica se o dispositivo que está sendo editado é meu
            verificarDonoDispositivo($conn, $id, $id_usuario);

            // 1. Atualiza Dispositivo
            $sql1 = "UPDATE dispositivos SET nome_dispositivo=?, potencia_dispositivo=?, fk_cat_disp_id_cat=? WHERE id_dispositivo=?";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("sdii", $nome, $potencia, $id_cat, $id);
            if (!$stmt1->execute()) throw new Exception("Erro ao atualizar dados: " . $stmt1->error);
            $stmt1->close();

            // 2. Atualiza Vínculo (Ambiente e Quantidade)
            $sql2 = "UPDATE ambiente_dispositivo SET fk_ambi_id_ambi=?, quantidade=? WHERE fk_disp_id_disp=?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("iii", $id_amb, $quantidade, $id);
            if (!$stmt2->execute()) throw new Exception("Erro ao mover dispositivo: " . $stmt2->error);
            $stmt2->close();

            $_SESSION['sucesso'] = "Dispositivo atualizado!";

        // ====================================================
        // AÇÃO: CADASTRAR
        // ====================================================
        } else {
            // (A verificação do ambiente dono já foi feita acima)

            // 1. Insere Dispositivo
            $sql1 = "INSERT INTO dispositivos (nome_dispositivo, potencia_dispositivo, fk_cat_disp_id_cat) VALUES (?, ?, ?)";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("sdi", $nome, $potencia, $id_cat);
            if (!$stmt1->execute()) throw new Exception("Erro ao inserir: " . $stmt1->error);
            
            $novo_id = $conn->insert_id;
            $stmt1->close();

            // 2. Insere Vínculo
            $sql2 = "INSERT INTO ambiente_dispositivo (fk_ambi_id_ambi, fk_disp_id_disp, quantidade) VALUES (?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("iii", $id_amb, $novo_id, $quantidade);
            if (!$stmt2->execute()) throw new Exception("Erro ao vincular ambiente: " . $stmt2->error);
            $stmt2->close();

            $_SESSION['sucesso'] = "Dispositivo criado!";
        }

        $conn->commit();
        header("Location: ../views/dispositivos.php");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['erros'] = [$e->getMessage()];
        
        if ($acao === 'editar' && isset($id)) {
            header("Location: ../views/gerencia_dispositivos.php?id=" . $id);
        } else {
            header("Location: ../views/dispositivos.php");
        }
        exit;
    }
} else {
    header("Location: ../views/dashboard.php");
    exit;
}
?>