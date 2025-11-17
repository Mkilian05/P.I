// dispositivos.js - Específico para a página de Dispositivos

document.addEventListener('DOMContentLoaded', () => {
    
    // Pega os elementos do DOM
    const addDispositivoCard = document.getElementById('addDispositivoCard');
    const modalDispositivo = document.getElementById('modalDispositivo');
    const modalCloseBtn = document.getElementById('modalCloseBtn');
    const modalCancelBtn = document.getElementById('modalCancelBtn');

    // Função para ABRIR o modal
    const abrirModal = () => {
        if (modalDispositivo) {
            modalDispositivo.classList.remove('hidden');
        }
    };

    // Função para FECHAR o modal
    const fecharModal = () => {
        if (modalDispositivo) {
            modalDispositivo.classList.add('hidden');
        }
    };

    // 1. Abrir o modal ao clicar no card "Adicionar"
    if (addDispositivoCard) {
        addDispositivoCard.addEventListener('click', (event) => {
            event.preventDefault(); // Impede que o link (href="#") mude a URL
            abrirModal();
        });
    }

    // 2. Fechar o modal ao clicar no 'X'
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', fecharModal);
    }

    // 3. Fechar o modal ao clicar em "Cancelar"
    if (modalCancelBtn) {
        modalCancelBtn.addEventListener('click', fecharModal);
    }

    // 4. Fechar o modal ao clicar FORA dele (no backdrop)
    if (modalDispositivo) {
        modalDispositivo.addEventListener('click', (event) => {
            // Verifica se o clique foi exatamente no 'modal-backdrop' (o fundo)
            // e não nos filhos dele (o 'modal-content')
            if (event.target === modalDispositivo) {
                fecharModal();
            }
        });
    }

    // (Opcional) Fechar o modal ao pressionar a tecla 'Esc'
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modalDispositivo.classList.contains('hidden')) {
            fecharModal();
        }
    });

});