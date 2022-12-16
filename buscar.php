<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");

include_once 'conexao.php';

$response_json = file_get_contents("php://input");
$dados = json_decode($response_json, true);

$code = $conn->prepare("SELECT infos FROM tb_dados_gerais WHERE data = :date");
$code->bindParam(':date', $dados['data']);
$code->execute();
$results = json_decode($code->fetchColumn(), true);

foreach ($results['clientes'] as $key => $info) {
    $results['clientes'][$key]['cliente'] = $key;
}

http_response_code(200);
echo json_encode($results) ?? null;