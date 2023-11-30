
document.addEventListener('DOMContentLoaded', function () {
    const cadastroForm = document.getElementById('cadastroForm');

    if (cadastroForm) {
        cadastroForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Impede o envio do formulário para realizar a validação antes do envio.

            const formData = new FormData(cadastroForm);

            // Use a opção credentials: 'same-origin' se você estiver no mesmo domínio
            fetch('server.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin', // Use se estiver no mesmo domínio
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Redireciona para a página de sucesso
                    window.location.href = data.redirect;
                } else {
                    // Exibe mensagem de erro
                    alert(data.error || 'Erro ao cadastrar estudante. Por favor, tente novamente.');
                }
            })
            .catch(error => {
                console.error('Erro durante o cadastro do estudante:', error);
                alert('Erro na requisição. Verifique o console para mais detalhes.');
            });
        });
    }
});