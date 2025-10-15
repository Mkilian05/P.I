<?php


// --- DADOS DE CONEXÃO ---
$db_host = 'localhost';
$db_name = 'wattsup';
$db_user = 'root';
$db_pass = '';

// Define o DSN (Data Source Name)
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

// Opções do PDO para um comportamento mais robusto
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Define o modo de busca padrão como array associativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desativa a emulação de prepared statements para segurança
];

try {
    // Tenta criar a conexão PDO
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    
    // Se chegar aqui, a conexão foi bem-sucedida!
    // Você pode remover a linha abaixo em um ambiente de produção.
    echo "Conexão com o banco de dados '{$db_name}' realizada com sucesso!";

} catch (PDOException $e) {
    // Se a conexão falhar, captura a exceção e exibe uma mensagem de erro genérica.
    // Em um ambiente de produção, é recomendado logar o erro em vez de exibi-lo na tela.
    error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
    die("Erro: Não foi possível conectar ao banco de dados. Por favor, tente novamente mais tarde.");
}

// A variável $pdo está pronta para ser usada em outros arquivos para executar queries.
?>