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

        $this->xmlService = new XmlService();
    }

    public function getInvoice(string $chave){
        $this->tools->model('55');

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

    public function generateXml(array $data, array $venda, ?array $destinatario, array $produtos, int $type = 55){
        $this->tools->model((string)$type);

        $nfe = new Make();
        $stdInfNFe = new \stdClass();
        $stdInfNFe->versao = '4.00';
        $stdInfNFe->Id = null;
        $stdInfNFe->pk_nItem = '';
        $infNFe = $nfe->taginfNFe($stdInfNFe);

        $stdIde = new \stdClass();
        $stdIde->cUF = $_ENV['UF'];
        $stdIde->cNF = random_int(10000000, 99999999);
        $stdIde->natOp = $data['nat_op'];

        $stdIde->mod = $type;
        $stdIde->serie = 1;
        $stdIde->nNF = $data['nNF'];
        $stdIde->dhEmi = date("Y-m-d\TH:i:sP");
        $stdIde->dhSaiEnt = date("Y-m-d\TH:i:sP");
        $stdIde->tpNF = 1; 

        $stdIde->idDest = $_ENV['UF'];
		$stdIde->cMunFG = $_ENV['CODIGO'];
		$stdIde->tpImp = 1;
		$stdIde->tpEmis = 1;
		$stdIde->cDV = 0;
		$stdIde->tpAmb = $_ENV['AMBIENTE'];
		$stdIde->finNFe = 1;
		$stdIde->indFinal = 1;
		$stdIde->indPres = 1;
		$stdIde->indIntermed = 0;
		$stdIde->procEmi = '0';
		$stdIde->verProc = '3.10.31';
		$tagide = $nfe->tagide($stdIde);

    }

}