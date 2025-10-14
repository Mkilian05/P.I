document.getElementById("formCadastro").addEventListener("submit", function(event) {
  event.preventDefault();

  const senha = document.getElementById("senha").value;
  const confirmarSenha = document.getElementById("confirmarSenha").value;
  const nome = document.getElementById("nome").value;

  if (senha !== confirmarSenha) {
    alert("As senhas não coincidem! Verifique e tente novamente.");
    return;
  }

  alert(`Cadastro realizado com sucesso! Bem-vindo(a), ${nome}!`);
  this.reset();
});

// Animação de entrada para os inputs
window.addEventListener("DOMContentLoaded", () => {
  const inputs = document.querySelectorAll(".form-control");
  inputs.forEach((input, index) => {
    setTimeout(() => {
      input.classList.add("input-appear");
    }, index * 150); // delay escalonado para efeito sequencial
  });
});
