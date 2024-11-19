
// Preview da foto
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('fotoPreview');
    const placeholder = document.getElementById('fotoPlaceholder');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
});

// Máscara para CPF
document.getElementById('cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        e.target.value = value;
    }
});

// Máscara para telefone
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        e.target.value = value;
    }
});

function validateForm(event) {
    event.preventDefault();
    
    const nome = document.getElementById('nome').value;
    const cpf = document.getElementById('cpf').value;
    const email = document.getElementById('email').value;
    const telefone = document.getElementById('telefone').value;
    const especialidades = document.querySelectorAll('input[name="especialidades"]:checked');
    const formError = document.getElementById('formError');
    const successMessage = document.getElementById('successMessage');
    
    formError.style.display = 'none';
    successMessage.style.display = 'none';
    
    // Validações
    if (nome.length < 3) {
        formError.textContent = 'Nome deve ter pelo menos 3 caracteres';
        formError.style.display = 'block';
        return false;
    }

    if (cpf.replace(/\D/g, '').length !== 11) {
        formError.textContent = 'CPF inválido';
        formError.style.display = 'block';
        return false;
    }

    if (!email.includes('@') || !email.includes('.')) {
        formError.textContent = 'E-mail inválido';
        formError.style.display = 'block';
        return false;
    }

    if (telefone.replace(/\D/g, '').length < 10) {
        formError.textContent = 'Telefone inválido';
        formError.style.display = 'block';
        return false;
    }

    if (especialidades.length === 0) {
        formError.textContent = 'Selecione pelo menos uma especialidade';
        formError.style.display = 'block';
        return false;
    }

    // Se passou por todas as validações
    successMessage.style.display = 'block';
    // Aqui você pode adicionar o código para enviar os dados para o servidor
    console.log('Cadastro realizado com sucesso!');
    
    return false;
}
