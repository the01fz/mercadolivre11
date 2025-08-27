<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);

    if (strlen($cpf) !== 11) {
        echo json_encode(['error' => 'CPF inválido']);
        exit;
    }

    // ✅ Novo token atualizado
    $token = 'bd4156f0b9a8ff83263167abce734f88fa12a56f706440b27f4d167222fb95ef';
    $url = "https://magmadatahub.com/api.php?token={$token}&cpf={$cpf}";

    $response = file_get_contents($url);

    if ($response === FALSE) {
        echo json_encode(['error' => 'Erro ao consultar API']);
        exit;
    }

    // ✅ Debug temporário — para ver o que está vindo da API
    print_r($response);
    exit;

    // Se quiser remover o debug depois, volte para:
    // echo $response;
} else {
    echo json_encode(['error' => 'Método inválido']);
}
