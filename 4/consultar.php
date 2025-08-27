<?php
header('Content-Type: application/json');

// Verifica se recebeu CPF via POST
$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;

if (!$cpf) {
    echo json_encode(["status" => 400, "erro" => "CPF não enviado"]);
    exit;
}

// Limpa CPF para conter apenas números
$cpf = preg_replace('/[^0-9]/', '', $cpf);

// Valida CPF
if (strlen($cpf) != 11) {
    echo json_encode(["status" => 400, "erro" => "CPF inválido"]);
    exit;
}

// Função para formatar CPF
function formatarCPF($cpf) {
    return substr($cpf, 0, 3) . '.' .
           substr($cpf, 3, 3) . '.' .
           substr($cpf, 6, 3) . '-' .
           substr($cpf, 9, 2);
}

// Dados da API externa
$token = '6824c087bfdc3ce2f39998163d99a704';
$url = "https://magmadatahub.com/api.php?token=$token&cpf=$cpf";

// Faz a requisição CURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Processa a resposta
if ($httpCode === 200 && $response) {
    $data = json_decode($response, true);

    if (isset($data['nome']) && !empty($data['nome'])) {
        echo json_encode([
            "status" => 200,
            "cpf" => formatarCPF($data['cpf'] ?? $cpf),
            "nome" => $data['nome'] ?? '',
            "nascimento" => $data['nascimento'] ?? '',
            "sexo" => $data['sexo'] ?? '',
            "nome_mae" => $data['nome_mae'] ?? ''
        ]);
    } else {
        echo json_encode(["status" => 404, "erro" => "Dados não encontrados para este CPF"]);
    }
} else {
    echo json_encode(["status" => $httpCode, "erro" => "Erro na comunicação com a API externa"]);
}
?>
