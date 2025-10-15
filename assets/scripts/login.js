// ⚙️ Animação de entrada para os inputs
window.addEventListener("DOMContentLoaded", () => {
  const inputs = document.querySelectorAll(".form-control");
  inputs.forEach((input, index) => {
    setTimeout(() => {
      input.classList.add("input-appear");
    }, index * 150);
  });
});

// ✅ Validação leve antes do envio (sem impedir o PHP)
document.getElementById("formLogin").addEventListener("submit", function (event) {
  const email = document.getElementById("email").value.trim();
  const senha = document.getElementById("senha").value.trim();

  if (email === "" || senha === "") {
    event.preventDefault(); // só bloqueia se estiver faltando algo
    alert("Preencha todos os campos!");
  }
});
