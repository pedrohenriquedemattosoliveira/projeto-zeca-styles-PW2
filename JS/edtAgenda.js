document.addEventListener('DOMContentLoaded', function() {
    // Serviços
    const serviceOptions = document.querySelectorAll('.service-option');
    
    serviceOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Se for o pacote completo, desmarca todos os outros
            if(this.dataset.service === 'completo') {
                serviceOptions.forEach(opt => {
                    if(opt.dataset.service !== 'completo') {
                        opt.classList.remove('selected');
                    }
                });
                this.classList.toggle('selected');
            } else {
                // Se selecionar qualquer outro serviço, desmarca o pacote completo
                const pacoteCompleto = document.querySelector('[data-service="completo"]');
                pacoteCompleto.classList.remove('selected');
                this.classList.toggle('selected');
            }
        });
    });

    // Status
    const statusOptions = document.querySelectorAll('.status-option');
    
    statusOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove a classe 'selected' de todas as opções
            statusOptions.forEach(opt => opt.classList.remove('selected'));
            // Adiciona a classe 'selected' apenas na opção clicada
            this.classList.add('selected');
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    // Função genérica para lidar com seleção única
    function handleSingleSelection(elements) {
        elements.forEach(element => {
            element.addEventListener('click', function() {
                // Remove a seleção de todos os elementos
                elements.forEach(el => el.classList.remove('selected'));
                // Adiciona a seleção apenas no elemento clicado
                this.classList.add('selected');
            });
        });
    }

    // Aplicar seleção única aos serviços
    const serviceOptions = document.querySelectorAll('.service-option');
    handleSingleSelection(serviceOptions);

    // Aplicar seleção única aos status
    const statusOptions = document.querySelectorAll('.status-option');
    handleSingleSelection(statusOptions);
});