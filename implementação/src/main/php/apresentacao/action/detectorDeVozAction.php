<?php
if (isset($_POST['start'])) {
    $audioText = $_POST['captured_text'];

    $to = 'joao.macedo@elastic.fit';
    $subject = 'Processo E-lastic';
    $message = "Texto capturado do áudio:\n\n" . $audioText;
    $headers = 'From: lucas.ahid.contato@gmail.com' . "\r\n" .
               'Reply-To: lucas.ahid.contato@gmail.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    $servername = "localhost";
    $username = "Sys";
    $password = "G@lvao123";
    $dbname = "E-lastic";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    $sql = "INSERT INTO audio_data (audio_text) VALUES ('$audioText')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro adicionado ao banco de dados com sucesso.";
    } else {
        echo "Erro ao adicionar registro ao banco de dados: " . $conn->error;
    }

    $conn->close();
}
?>
