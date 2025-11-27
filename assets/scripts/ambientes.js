// assets/js/ambientes.js

document.addEventListener('DOMContentLoaded', () => {
    
    const addCard = document.getElementById('addAmbienteCard');
    const modal = document.getElementById('modalAmbiente');
    const closeBtn = document.getElementById('modalCloseBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');

    // Função para FECHAR o modal
    const fechar = () => {
        if (modal) modal.classList.add('hidden');
    };

    // Função para ABRIR o modal (COM PROTEÇÃO)
    const abrir = (event) => {
        // 1. Impede comportamentos padrões (como seguir links)
        if(event) event.preventDefault(); 
        
        // 2. A MÁGICA: Impede que o clique "suba" para o pai ou scripts globais
        // Isso evita que scripts genéricos de ".card" tentem redirecionar a página
        if(event) event.stopPropagation(); 

        if (modal) modal.classList.remove('hidden');
    };

    // Eventos
    if (addCard) {
        addCard.addEventListener('click', abrir);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', fechar);
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Evita que o botão recarregue a página
            fechar();
        });
    }

    // Fechar ao clicar fora (no fundo escuro)
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) fechar();
        });
    }
});