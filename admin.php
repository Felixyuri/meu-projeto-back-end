<?php

error_reporting(0);
ini_set(“display_errors”, 0 );

class Admin {
    
    public function getCurrentContent() {
        echo('123');
        require_once('connection.php');
		$conn = Database::connectionPDO();

        $dados = json_decode(file_get_contents('https://www.ibridge.com.br/dados.json'), true);

        // query para buscar as informaçoes gerais do banco;
        $query_geral = "SELECT data FROM tb_dados_gerais";
        $code = $conn->prepare($query_geral);
        $code->execute();
        $results = $code->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dados as $key => $info) {

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

        foreach ($dados[0]['clientes'] as $key => $info) {
            $dados[0]['clientes'][$key]['cliente'] = $key;
        }

        return $dados[0] ?? null;
    }

    public function getData($info) {
        require_once('connection.php');
		$conn = Database::connectionPDO(); 

        $code = $conn->prepare("SELECT infos FROM tb_dados_gerais WHERE data = :date");
        $code->bindParam(':date', $info['data']);
        $code->execute();
        $results = json_decode($code->fetchColumn(), true);

        foreach ($results['clientes'] as $key => $info) {
            $results['clientes'][$key]['cliente'] = $key;
        }

        return $results ?? null;
    }

    public function getPie() {
        require_once('connection.php');
		$conn = Database::connectionPDO(); 

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

        return $org ?? null;
    }

    public function getLine() {
        require_once('connection.php');
		$conn = Database::connectionPDO(); 

        $code = $conn->prepare("SELECT infos FROM tb_dados_gerais");
        $code->execute();
        $results = $code->fetchAll(PDO::FETCH_ASSOC);

        $org = [];
        $keys = array('geral');

        foreach ($results as $key => $info) {
            $info = json_decode($info['infos'], true);

            $org[$key] = $info['geral'];
        }

        return $org ?? null;
    }

    public function getBar() {
        require_once('connection.php');
		$conn = Database::connectionPDO(); 
        
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

        return $org ?? null;
    }

}
