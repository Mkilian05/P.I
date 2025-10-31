document.addEventListener("DOMContentLoaded", () => {
  const btns = document.querySelectorAll(".btn-outline, .btn-primary");
  btns.forEach(btn => {
    btn.addEventListener("click", () => {
      btn.classList.add("clicked");
      setTimeout(() => btn.classList.remove("clicked"), 400);
    });
  });
});