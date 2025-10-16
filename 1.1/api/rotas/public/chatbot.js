function enviarMensagem(mensagem) {
    fetch("../api/rotas/pesquisa.php?texto=" + encodeURIComponent(mensagem))
        .then(res => {
            if (!res.ok) throw new Error("HTTP " + res.status);
            return res.json(); 
        })
        .then(data => {
            console.log("Resposta do servidor:", data);

            // Mostra a resposta crua para debug
            adicionarMensagem("🤖", "Servidor respondeu: " + JSON.stringify(data));

            if (data.resultados && data.resultados.length > 0) {
                data.resultados.forEach(item => {
                    adicionarMensagem("🤖",
                        `Curso: ${item.curso} | Área: ${item.area} | Nível: ${item.nivel} | Escola: ${item.escola} (${item.provincia})`
                    );
                });
            } else if (data.total === 0) {
                adicionarMensagem("🤖", "Não encontrei cursos com esse termo.");
            }
        })
        .catch(err => {
            console.error("Erro ao comunicar:", err);
            adicionarMensagem("🤖", "Erro ao comunicar com o servidor. (Ver console)");
        });
}

function adicionarMensagem(remetente, texto) {
    const chat = document.getElementById("chat");
    const linha = document.createElement("div");
    linha.textContent = remetente + ": " + texto;
    chat.appendChild(linha);
}
