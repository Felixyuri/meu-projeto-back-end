<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");

include_once 'conexao.php';

$code = $conn->prepare("SELECT infos, MAX(data) FROM tb_dados_gerais");
$code->execute();
$results = json_decode($code->fetchColumn(), true);

$org = [];
$keys = array('ocorrencias_sem_contato', 'ocorrencias_com_contato', 'ocorrencias_abordagem', 'ocorrencias_fechamento');

foreach ($results['geral'] as $key => $info) {
    if(in_array($key, $keys)) {
        $org[$key] = $info;
    }
}

http_response_code(200);
echo json_encode($org) ?? null;