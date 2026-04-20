<?php

namespace App\Infra\Services\NFe;

use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use App\Infra\Services\Xml\XmlService;
use App\Infra\Services\Log\LogService;

class NFeService {

    protected $tools;
    protected $xmlService;

    public function __construct(){
        $this->tools = new Tools('{
            "atualizacao": "2026-01-01",
            "tpAmb": ' .$_ENV['AMBIENTE']. ',
            "razaosocial": "'. $_ENV['RAZAO_SOCIAL']. '",
            "siglaUF": "'. $_ENV['UF']. '",
            "cnpj": "'. $_ENV['CNPJ']. '",
            "schemes": "PL_009_V4",
            "versao": "4.00",
            "tokenIBPT": "",
            "CSC": "",
            "CSCid": ""
        }', Certificate::readPfx(file_get_contents(__DIR__.'/../../../../'.$_ENV['CERTIFICATE']), "123456"));

        $this->tools->model('55');
        $this->xmlService = new XmlService();
    }

    public function getInvoice(string $chave){
        try {
            $response  = $this->tools->sefazDownload($chave);

            $stdCl = new Standardize($response);
            
            $arr = $stdCl->toArray();

            $xml = $this->xmlService->saveXml((string)$arr['loteDistDFeInt']['docZip']);

            $nfeArray = $this->xmlService->convertXmltoArray($xml);

            return [
                'xml' => $xml,
                'nfeArray' => $nfeArray
            ];
        }catch (\Exception $e) {
            LogService::logError($e->getMessage());
            return null;
        }
    }

}