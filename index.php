<?php

// headers;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// conexão com o banco;
include_once 'conexao.php';

// query para buscar os ultimos dados inseridos no banco;
$query_geral = "SELECT max(current_date) FROM tb_dados_gerais";
$code = $conn->prepare($query_geral);
$code->execute();
$results = $code->fetchAll(PDO::FETCH_ASSOC);
var_dump($results);
exit;
if($results) {
    foreach ($results as $key => $result) {

        $lista["geral"][$result["id"]] = [
            'id' => $result["id"],
            'data' => $result["hora"]
        ];
    }

    http_response_code(200);

    echo json_encode($lista);
}