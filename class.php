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
$keys = array('chamadas_falha_operadora', 'chamadas_telefone_incorreto', 'chamadas_nao_atendida', 'chamadas_atendimento_maquina', 'chamadas_atendimento_humano', 'chamadas_abandono_pre_fila', 'chamadas_abandono_fila', 'chamadas_atendimento_pa');

foreach ($results['geral'] as $key => $info) {
    if(in_array($key, $keys)) {
        $org[$key] = $info;
    }
}

http_response_code(200);
echo json_encode($org) ?? null;