// === EFEITOS DE ENTRADA ===
document.addEventListener("DOMContentLoaded", () => {
  const fadeElements = document.querySelectorAll(".fade-in");
  fadeElements.forEach((el, index) => {
    setTimeout(() => {
      el.classList.add("visible");
    }, 150 * index);
  });
});

// === MENU LATERAL RESPONSIVO (caso adicione no futuro) ===
const menuToggle = document.querySelector("#menu-toggle");
const sidebar = document.querySelector(".sidebar");

if (menuToggle && sidebar) {
  menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("open");
  });
}

// === ANIMAÇÃO AO PASSAR O MOUSE NAS LINHAS DA TABELA ===
const tableRows = document.querySelectorAll(".table-hover tbody tr");
tableRows.forEach((row) => {
  row.addEventListener("mouseenter", () => {
    row.style.backgroundColor = "rgba(124, 58, 237, 0.1)";
  });
  row.addEventListener("mouseleave", () => {
    row.style.backgroundColor = "transparent";
  });
});

// === CONTADORES ANIMADOS (usuários, casas, etc) ===
const counters = document.querySelectorAll(".counter");
counters.forEach((counter) => {
  const updateCount = () => {
    const target = +counter.getAttribute("data-target");
    const count = +counter.innerText;
    const speed = 50; // quanto menor, mais rápido

    if (count < target) {
      counter.innerText = Math.ceil(count + (target - count) / speed);
      setTimeout(updateCount, 10);
    } else {
      counter.innerText = target;
    }
  };
  updateCount();
});
