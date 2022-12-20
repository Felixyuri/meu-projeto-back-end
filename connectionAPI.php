<?php

error_reporting(0);
ini_set(“display_errors”, 0 );

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE");

$function  = json_decode(file_get_contents("php://input"), true)['function'];
$content[0]   = json_decode(json_decode(file_get_contents("php://input"), true)['content'], true);

require_once('admin.php');

class Rest {
    // Executa a Classe e Função recebida por URL e envia os parâmetros por GET ou POST
    public static function open($function, $content) {
        try {
            $class = 'Admin';

            // Retorna o sucesso da execução
            if (class_exists($class)) {
                if (method_exists($class, $function)) {
                    $return = call_user_func_array(array(new $class, $function), $content);

                    return json_encode(array('status' => 'success', 'data' => $return));
                } else {
                    return json_encode(array('status' => 'erro', 'data' => 'Método inexistente!'));
                }
            } else {
                return json_encode(array('status' => 'erro', 'data' => 'Classe inexistente!'));
            }
        } catch (Exception $e) {	
            // Retorna o erro caso exista
            $errorMessage 	= $e->getMessage();

            echo json_encode(array('status' => 'erro', 'data' => $errorMessage));
        }
        
    }
}

if (isset($function)) {
    echo Rest::open($function, $content);
}