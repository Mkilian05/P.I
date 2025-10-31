// casas.js - Específico para a página de Casas
document.addEventListener('DOMContentLoaded', () => {
    const addHouseCard = document.getElementById('addHouseCard');

    if (addHouseCard) {
        // Exemplo de animação mais sutil ao clicar
        addHouseCard.addEventListener('click', (event) => {
            // Previne o comportamento padrão do link para fazer a animação primeiro
            event.preventDefault(); 
            
            // Adiciona uma classe para o efeito de clique
            addHouseCard.classList.add('clicked');

            // Redireciona após a animação (ex: 300ms)
            setTimeout(() => {
                window.location.href = addHouseCard.href;
            }, 300);
        });

        // CSS para a animação de clique (você pode adicionar no casas.css)
        // .add-house-card.clicked {
        //     animation: pulse 0.3s ease-in-out;
        // }
        // @keyframes pulse {
        //     0% { transform: scale(1.03); }
        //     50% { transform: scale(0.98); }
        //     100% { transform: scale(1.03); }
        // }
        
        // Ou uma animação JS direta
        addHouseCard.addEventListener('mousedown', () => {
            addHouseCard.style.transform = 'scale(0.98)';
        });

        addHouseCard.addEventListener('mouseup', () => {
            addHouseCard.style.transform = 'scale(1.03)'; // Volta ao estado de hover
        });

        addHouseCard.addEventListener('mouseleave', () => {
            addHouseCard.style.transform = 'scale(1)'; // Volta ao estado normal
        });
    }
});