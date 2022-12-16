<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");

include_once 'conexao.php';

$code = $conn->prepare("SELECT infos FROM tb_dados_gerais");
$code->execute();
$results = $code->fetchAll(PDO::FETCH_ASSOC);

$org = [];
$keys = array('geral');

foreach ($results as $key => $info) {
    $info = json_decode($info['infos'], true);

    $org[$key] = $info['geral'];
}

http_response_code(200);
echo json_encode($org) ?? null;