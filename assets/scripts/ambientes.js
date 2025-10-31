// ambientes.js - Específico para a página de Ambientes
document.addEventListener('DOMContentLoaded', () => {
    // Procura pelo ID do NOVO card
    const addAmbienteCard = document.getElementById('addAmbienteCard');

    if (addAmbienteCard) {
        // Previne o clique para fazer a animação
        addAmbienteCard.addEventListener('click', (event) => {
            event.preventDefault(); 
            addAmbienteCard.classList.add('clicked');

            // Redireciona após a animação
            setTimeout(() => {
                window.location.href = addAmbienteCard.href;
            }, 300);
        });

        // Efeitos de "pressionar" com o mouse
        addAmbienteCard.addEventListener('mousedown', () => {
            addAmbienteCard.style.transform = 'scale(0.98)';
        });

        addAmbienteCard.addEventListener('mouseup', () => {
            addAmbienteCard.style.transform = 'scale(1.03)';
        });

        addAmbienteCard.addEventListener('mouseleave', () => {
            addAmbienteCard.style.transform = 'scale(1)';
        });
    }
});