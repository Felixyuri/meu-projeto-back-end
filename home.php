<?php

// headers;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");

// conexão com o banco;
include_once 'conexao.php';
$moment = date('Y-m-d');

// pega as informaçoes recebidas da requisição do react;
$response_json  = file_get_contents("php://input");
$dados          = json_decode($response_json, true);

// percorre os dados vindos do react, para que possa ser verificado as datas e com isso inserir no banco as informacoes que ainda não foram colocadas;
foreach ($dados as $key => $info) {

    // query para buscar as informaçoes gerais do banco;
    $query_geral = "SELECT data FROM tb_dados_gerais";
    $code = $conn->prepare($query_geral);
    $code->execute();
    $results = $code->fetchAll(PDO::FETCH_ASSOC);

    // setar o array que serve para verificar se a data já foi setada no banco;
    $currentDates = [];

    // percorre o resultado da query para pegar as datas das informaçoes ja inseridas;
    foreach($results as $key => $result) {
        $currentDates[$key] = $result['data'];
    }

    if(array_search($info['geral']['data'], $currentDates) === false) {
        $code = $conn->prepare("INSERT INTO tb_dados_gerais(infos, data) VALUES(:infos, :moment)");
        $code->bindParam(':infos', json_encode($info));
        $code->bindParam(':moment', $info['geral']['data']);
        $code->execute();
    }
}

// coloca o id de cada cliente no array;
foreach ($dados[0]['clientes'] as $key => $info) {
    $dados[0]['clientes'][$key]['cliente'] = $key;
}

// resposta para dizer que deu tudo certo;
http_response_code(200);
echo json_encode($dados[0]) ?? null;