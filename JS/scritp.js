function validateForm(event) {
    event.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const successMessage = document.getElementById('successMessage');
    
    // Reset error messages
    emailError.style.display = 'none';
    passwordError.style.display = 'none';
    successMessage.style.display = 'none';
    
    let isValid = true;
    
    // Validate email
    if (!email.includes('@') || !email.includes('.')) {
        emailError.textContent = 'Por favor, insira um e-mail válido';
        emailError.style.display = 'block';
        isValid = false;
    }
    
    // Validate password
    if (password.length < 6) {
        passwordError.textContent = 'A senha deve ter pelo menos 6 caracteres';
        passwordError.style.display = 'block';
        isValid = false;
    }
    
    if (isValid) {
        // Simulação de login bem-sucedido
        successMessage.style.display = 'block';
        document.getElementById('loginForm').reset();
        
        // Aqui você pode adicionar a lógica de autenticação real
        // Por exemplo, enviando os dados para um servidor
        console.log('Login realizado:', { email, password });
    }
    
    return false;
}