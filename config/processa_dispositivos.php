<?php
// /config/processa_dispositivo.php

session_start();
require_once __DIR__ . '/../config/database.php'; // Seu arquivo de conexão $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. COLETAR DADOS
    $nome       = $_POST['dispositivoNome'];
    $potencia   = $_POST['dispositivoPotencia'];
    $id_cat     = $_POST['dispositivoTipo'];
    $id_amb     = $_POST['dispositivoAmbiente'];
    $quantidade = $_POST['dispositivoQuantidade']; 

    $erros = [];

    // 4. VALIDAÇÃO (ESSA PARTE É CRUCIAL)
    if (empty($nome) || empty($potencia) || empty($id_cat) || empty($id_amb) || empty($quantidade)) {
        $erros[] = "Todos os campos são obrigatórios!";
    }
    if ($quantidade <= 0) {
        $erros[] = "A quantidade deve ser pelo menos 1.";
    }
    if (!is_numeric($potencia) || !is_numeric($id_cat) || !is_numeric($id_amb) || !is_numeric($quantidade)) {
        $erros[] = "Dados inválidos fornecidos.";
    }

    if (!empty($erros)) {
        $_SESSION['erros'] = $erros;
        header("Location: ../views/dispositivos.php");
        exit;
    }

    // 5. TRANSAÇÃO DE BANCO DE DADOS (COM TODOS OS NOMES CORRIGIDOS)
    try {
        $conn->begin_transaction();

        // 1. Inserir na tabela 'dispositivos' (fk_id_categoria)
        $sql1 = "INSERT INTO dispositivos (nome_dispositivo, potencia_dispositivo, fk_id_categoria) 
                 VALUES (?, ?, ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("sdi", $nome, $potencia, $id_cat); 
        
        if (!$stmt1->execute()) {
            throw new Exception("Erro ao inserir dispositivo: " . $stmt1->error);
        }
        $stmt1->close();
        
        // 2. Pegar o ID do dispositivo que acabamos de criar
        $novo_id_dispositivo = $conn->insert_id;

        // 3. Inserir na tabela 'ambiente_dispositivo' (fk_id_ambiente, fk_id_dispositivo)
        $sql2 = "INSERT INTO ambiente_dispositivo (fk_id_ambiente, fk_id_dispositivo, quantidade) 
                 VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iii", $id_amb, $novo_id_dispositivo, $quantidade); 
        
        if (!$stmt2->execute()) {
            throw new Exception("Erro ao associar dispositivo ao ambiente: " . $stmt2->error);
        }
        $stmt2->close();

        // 4. Commit
        $conn->commit();
        
        $_SESSION['sucesso'] = "Dispositivo cadastrado com sucesso!";

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['erros'] = ["Erro ao salvar o dispositivo: " . $e->getMessage()];
    }

    header("Location: ../views/dispositivos.php");
    exit;

} else {
    header("Location: ../views/dashboard.php");
    exit;
}