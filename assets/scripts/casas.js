// assets/js/casas.js

document.addEventListener('DOMContentLoaded', () => {
    
    const addCard = document.getElementById('addCasaCard');
    const modal = document.getElementById('modalCasa');
    const closeBtn = document.getElementById('modalCloseBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');

    // Função para FECHAR
    const fechar = () => {
        if (modal) modal.classList.add('hidden');
    };

    // Função para ABRIR (com proteção)
    const abrir = (event) => {
        if(event) {
            event.preventDefault();
            event.stopPropagation(); // Impede conflito com links globais
        }
        if (modal) modal.classList.remove('hidden');
    };

    // Eventos
    if (addCard) addCard.addEventListener('click', abrir);
    if (closeBtn) closeBtn.addEventListener('click', fechar);
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', (e) => {
            e.preventDefault(); 
            fechar();
        });
    }

    // Fechar ao clicar fora
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) fechar();
        });
    }
});