document.getElementById("formLogin").addEventListener("submit", function(event) {
  event.preventDefault();

  const email = document.getElementById("email").value;
  const senha = document.getElementById("senha").value;

  if (email === "" || senha === "") {
    alert("Preencha todos os campos!");
    return;
  }

  alert(`Bem-vindo(a) de volta, ${email}!`);
  this.reset();
});

// Animação de entrada para os inputs
window.addEventListener("DOMContentLoaded", () => {
  const inputs = document.querySelectorAll(".form-control");
  inputs.forEach((input, index) => {
    setTimeout(() => {
      input.classList.add("input-appear");
    }, index * 150);
  });
});
