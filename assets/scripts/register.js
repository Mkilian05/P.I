// Adiciona o "ouvinte" de evento ao formulário de cadastro
document.getElementById("formCadastro").addEventListener("submit", function(event) {
  // Pega os valores dos campos de senha
  const senha = document.getElementById("senha").value;
  const confirmarSenha = document.getElementById("confirmarSenha").value;

  // 1. VERIFICAÇÃO: As senhas não coincidem?
  if (senha !== confirmarSenha) {
    // Se não coincidirem, mostra um alerta para o usuário
    alert("As senhas não coincidem! Verifique e tente novamente.");
    
    // E o mais importante: impede o envio do formulário para o PHP
    event.preventDefault(); 
    return; // Para a execução da função aqui
  }

  // 2. SE AS SENHAS COINCIDIREM: O script não faz nada!
  // Ele simplesmente termina, e o navegador continua com a ação padrão,
  // que é enviar os dados do formulário para "processa_cadastro.php".
  // A linha de sucesso "alert(`Cadastro realizado...`)" foi removida 
  // porque a resposta real virá do PHP após a página recarregar.
});

// Animação de entrada para os inputs (esta parte pode continuar, é só estética)
window.addEventListener("DOMContentLoaded", () => {
  const inputs = document.querySelectorAll(".form-control");
  inputs.forEach((input, index) => {
    setTimeout(() => {
      input.classList.add("input-appear");
    }, index * 150);
  });
});