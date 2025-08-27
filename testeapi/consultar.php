<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);

    if (strlen($cpf) !== 11) {
        echo json_encode(['error' => 'CPF inválido']);
        exit;
    }

    $token = 'd129896ec839f47542bb85b64db8d0469ef29cd6ecb7f453bbd2c5dff1482162';
    $url = "https://magmadatahub.com/api.php?token={$token}&cpf={$cpf}";

    $response = file_get_contents($url);

    if ($response === FALSE) {
        echo json_encode(['error' => 'Erro ao consultar API']);
        exit;
    }

    echo $response;
} else {
    echo json_encode(['error' => 'Método inválido']);
}
