<?php

use PHPUnit\Framework\TestCase;

class DetectorDeVozActionTest extends TestCase
{
    public function testEnvioDeEmail()
    {
        // Dados para simular o envio de um formulário
        $_POST['captured_text'] = 'Texto de teste';

        // Captura a saída do script (pode ser usada para verificar a saída)
        ob_start();
        include 'detectorDeVozAction.php';
        $output = ob_get_clean();

        // Verifica se a saída contém a mensagem esperada
        $this->assertStringContainsString('Email enviado com anexo!', $output);
    }
}
