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
