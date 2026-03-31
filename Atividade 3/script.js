document.getElementById("f").addEventListener("submit", function (e) {
    let valido = true;
    const nome = document.getElementById("nome");
    const pfisica = document.getElementById("pfisica");
    const pjuridica = document.getElementById("pjuridica");
    const cpfCnpj = document.getElementById("cpf_cnpj");

    
    document.querySelectorAll(".erro").forEach(span => span.innerHTML = "");

    
    if (nome.value.trim() === "") {
        document.getElementById("msg-nome").innerHTML = "O nome é obrigatório.";
        valido = false;
    }

    
    if (!pfisica.checked && !pjuridica.checked) {
        document.getElementById("msg-tipo").innerHTML = "Selecione o tipo de pessoa.";
        valido = false;
    }

    const numeros = cpfCnpj.value.replace(/\D/g, '');
    if (numeros === "") {
        document.getElementById("msg-cpf_cnpj").innerHTML = "O campo CPF/CNPJ não pode estar vazio.";
        valido = false;
    } else if (pfisica.checked && numeros.length !== 11) {
        document.getElementById("msg-cpf_cnpj").innerHTML = "CPF deve ter 11 dígitos.";
        valido = false;
    } else if (pjuridica.checked && numeros.length !== 14) {
        document.getElementById("msg-cpf_cnpj").innerHTML = "CNPJ deve ter 14 dígitos.";
        valido = false;
    }

    if (!valido) {
        e.preventDefault(); 
    }
});