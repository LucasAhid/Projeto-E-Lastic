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
    $headers = 'From: seu_email@example.com' . "\r\n" .
        'Reply-To: seu_email@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Envia o email
    if (mail($to, $subject, $message, $headers)) {
        // Conexão com o banco de dados (substitua pelos seus dados)
        $servername = "localhost";
        $username = "Lucas_ahid";
        $password = "G@lvao123";
        $dbname = "PRJ_E_LASTIC";

        $conn = new mysqli($servername, $username, $password, $dbname);

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
        echo 'Ocorreu um erro ao enviar o email.';
    }
} else {
    echo 'Método inválido.';
}
?>
