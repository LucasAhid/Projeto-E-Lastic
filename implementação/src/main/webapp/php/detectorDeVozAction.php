<!DOCTYPE html>
<html>
<head>
    <title>E-LASTIC</title>
    <style>
        body {
            background-color: #f0f0f0;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            color: black;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        #buttons {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        #startBtn,
        #stopBtn,
        #sendBtn {
            background-color: #77b6ea;
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
        }

        #output {
            margin-top: 20px;
        }
        #startBtn[disabled], #sendBtn[disabled] {
            background-color: gray;
            color: white;
            cursor: not-allowed;
        }

        #stopBtn[disabled] {
            background-color: gray;
            color: white;
            cursor: not-allowed;
        }

        #startBtn:not([disabled]), #sendBtn:not([disabled]) {
            background-color: #77b6ea;
            color: black;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>APRESENTAÇÃO E-LASTIC</h1>
    <div id="buttons">
        <div class="button-group">
            <button id="startBtn">Iniciar</button>
            <button id="stopBtn" disabled>Parar</button>
        </div>
        <button id="sendBtn" disabled>Enviar</button>
    </div>
    <p id="output"></p>

    <form id="voiceForm" action="detectorDeVozAction.php" method="POST">
        <input type="hidden" name="captured_text">
        <button type="submit" id="hiddenSubmit" style="display: none;"></button>
    </form>

    <script>
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const sendBtn = document.getElementById('sendBtn');
        const output = document.getElementById('output');
        let recognition;

        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
        } else {
            alert('Navegador não suporta a API de reconhecimento de fala do Google.');
        }

        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'pt-BR';

        recognition.onstart = () => {
            startBtn.disabled = true;
            stopBtn.disabled = false;
            sendBtn.disabled = true;
            output.textContent = 'Escutando...';
        };

        recognition.onerror = event => {
            output.textContent = 'Erro ao capturar áudio: ' + event.error;
        };

        recognition.onend = () => {
            startBtn.disabled = false;
            stopBtn.disabled = true;
            sendBtn.disabled = false;
            output.textContent = '';
        };

        recognition.onresult = event => {
            let interimTranscript = '';
            let finalTranscript = '';

            for (let i = event.resultIndex; i < event.results.length; i++) {
                const transcript = event.results[i][0].transcript;
                if (event.results[i].isFinal) {
                    finalTranscript += transcript + ' ';
                } else {
                    interimTranscript += transcript;
                }
            }

            output.innerHTML = '<b>Texto reconhecido:</b><br>' + finalTranscript + '<i>' + interimTranscript + '</i>';
            sendBtn.addEventListener('click', () => {
                const formData = new FormData();
                formData.append('captured_text', finalTranscript);

                fetch('detectorDeVozAction.php', {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    // Processar a resposta do servidor, se necessário
                }).catch(error => {
                    console.error('Erro ao enviar dados para o servidor:', error);
                });
            });
        };

        startBtn.addEventListener('click', () => {
            recognition.start();
        });

        stopBtn.addEventListener('click', () => {
            recognition.stop();
        });
    </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe o texto capturado do JavaScript
    $capturedText = $_POST['captured_text'];

    // Obtém a hora da gravação
    date_default_timezone_set('America/Sao_Paulo');
    $recordingTime = date('Y-m-d H:i:s');

    // Configurações do email
    $to = 'joao.macedo@elastic.fit';
    $subject = 'Texto Capturado por E-LASTIC';
    $message = "Texto Capturado: \n\n" . $capturedText . "\n\nHora da Gravação: " . $recordingTime;
    $headers = 'From: lucas.ahid.contato@gmail.com' . "\r\n" .
        'Reply-To: lucas.ahid.contato@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

   // Lê o conteúdo do arquivo diretamente do diretório local
   $attachmentPath = 'C:\Users\Jessica\Desktop\LUCAS\SQL\Arquivos modelagem E-Lastic\arquivo.pdf';
   $attachmentContent = file_get_contents($attachmentPath);
   $attachmentEncoded = base64_encode($attachmentContent);

   // Monta a parte do anexo
   $attachment = "--attachment\r\nContent-Type: application/octet-stream; name=\"arquivo.pdf\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"arquivo.pdf\"\r\n\r\n" . chunk_split($attachmentEncoded) . "\r\n--attachment--";

   // Envia o email com anexo
   if (mail($to, $subject, $message, $headers, $attachment)) {
       echo 'Email enviado com anexo!';
    } else {
       echo 'Ocorreu um erro ao enviar o email com anexo.';
       }

        // Verifica a conexão
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Insere os dados na tabela
        $sql = "INSERT INTO TAB_REGISTRO (TEXTO, DATAHORA, Destinatario)
                VALUES ('$capturedText', '$recordingTime', '$to')";

        if ($conn->query($sql) === TRUE) {
            echo "Dados inseridos no banco com sucesso!";
        } else {
            echo "Erro ao inserir dados no banco: " . $conn->error;
        }

        $conn->close();
} else {
    echo 'Método inválido.';
}
?>
