<?php
$page = 'ambientes'; 
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

// SEGURANÇA: Pega o ID do usuário (ou usa 1 para testes se não tiver login ainda)
$id_usuario = $_SESSION['id_usuario'] ?? 1;

$erros = $_SESSION['erros'] ?? [];
$sucesso = $_SESSION['sucesso'] ?? null;
unset($_SESSION['erros'], $_SESSION['sucesso']);

// 1. Listar APENAS Ambientes do Usuário
$sql_ambientes = "
    SELECT 
        a.id_ambiente, 
        a.nome_ambiente, 
        k.nome_casa,
        COALESCE(SUM(ad.quantidade), 0) AS total_dispositivos
    FROM 
        ambiente AS a
    JOIN 
        casa AS k ON a.fk_casa_id_casa = k.id_casa 
    LEFT JOIN 
        ambiente_dispositivo AS ad ON a.id_ambiente = ad.fk_ambi_id_ambi
    WHERE 
        k.fk_usuario_id_usuario = ? 
        AND (a.is_deleted IS NULL OR a.is_deleted = 0)
    GROUP BY
        a.id_ambiente, a.nome_ambiente, k.nome_casa
    ORDER BY 
        k.nome_casa, a.nome_ambiente
";

$stmt = $conn->prepare($sql_ambientes);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$lista_ambientes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// 2. Listar APENAS Casas do Usuário (Para o Modal)
// Um usuário não pode cadastrar um ambiente na casa do vizinho
$sql_casas = "SELECT id_casa, nome_casa FROM casa WHERE fk_usuario_id_usuario = ? AND (is_deleted IS NULL OR is_deleted = 0)";
$stmt_casas = $conn->prepare($sql_casas);
$stmt_casas->bind_param("i", $id_usuario);
$stmt_casas->execute();
$lista_casas = $stmt_casas->get_result()->fetch_all(MYSQLI_ASSOC);

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar-nav.php';
?>

<main class="dashboard-content fade-in">

    <header class="top-header">
        <h1>Meus Ambientes</h1>
        <p>Gerencie os cômodos das suas residências.</p>
    </header>

    <?php if (!empty($erros)): ?>
        <div class="alerta erro">
            <?php foreach ($erros as $erro): echo "<p>" . htmlspecialchars($erro) . "</p>"; endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="alerta sucesso"><p><?php echo htmlspecialchars($sucesso); ?></p></div>
    <?php endif; ?>

    <section class="ambientes-grid"> 
        <?php if(empty($lista_ambientes)): ?>
            <p style="grid-column: 1/-1; text-align: center; color: #666; margin-top: 2rem;">Nenhum ambiente encontrado.</p>
        <?php endif; ?>

        <?php foreach ($lista_ambientes as $amb): ?>
            <div class="ambiente-card">
                <div class="ambiente-header">
                    <h2><?php echo htmlspecialchars($amb['nome_ambiente']); ?></h2>
                    <p style="font-size: 0.9rem; color: #666; margin-top: 5px;">
                        <i class="fas fa-home"></i> <?php echo htmlspecialchars($amb['nome_casa']); ?>
                    </p>
                </div>
                <a href="gerencia_ambientes.php?id=<?php echo $amb['id_ambiente']; ?>" class="btn-editar-card">
                    Editar
                </a>
            </div>
        <?php endforeach; ?>

        <div class="add-ambiente-card" id="addAmbienteCard">
            <div class="add-circle">
                <i class="fas fa-plus icon-plus"></i>
            </div>
            <p>Novo Ambiente</p>
        </div>
    </section>

</main>

<div class="modal-backdrop hidden" id="modalAmbiente">
    <div class="modal-content">
        <header class="modal-header">
            <h2>Cadastrar Ambiente</h2>
            <button class="modal-close-btn" id="modalCloseBtn">&times;</button>
        </header>

        <form class="modal-body" id="formAmbiente" method="POST" action="../config/processa_ambiente.php">
            <input type="hidden" name="acao" value="cadastrar">
            
            <div class="form-group">
                <label for="ambienteNome">Nome do Ambiente</label>
                <input type="text" id="ambienteNome" name="ambienteNome" placeholder="Ex: Cozinha" required>
            </div>

            <div class="form-group">
                <label for="ambienteCasa">Pertence a qual Casa?</label>
                <select id="ambienteCasa" name="ambienteCasa" required>
                    <option value="" disabled selected>Selecione...</option>
                    <?php foreach ($lista_casas as $casa): ?>
                        <option value="<?php echo $casa['id_casa']; ?>">
                            <?php echo htmlspecialchars($casa['nome_casa']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <footer class="modal-footer">
            <button class="btn-outline" id="modalCancelBtn">Cancelar</button>
            <button type="submit" form="formAmbiente" class="btn-primary">Salvar</button>
        </footer>
    </div>
</div>

<?php
include_once __DIR__ . '/../includes/footer.php';
$conn->close();
?>