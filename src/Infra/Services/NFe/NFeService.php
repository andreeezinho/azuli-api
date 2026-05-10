<?php

namespace App\Infra\Services\NFe;

use App\Domain\Models\Venda\Venda;
use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use App\Infra\Services\Xml\XmlService;
use App\Infra\Services\Log\LogService;
use NFePHP\DA\NFe\Danfe;
use NFePHP\DA\NFe\DanfeSimples;
use NFePHP\DA\NFe\Danfce;

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

    public function generateXml(array $data, Venda $venda, array $produtos, mixed $destinatario = null, int $type = 55){
        $this->tools->model((string)$type);

        $nfe = new Make();
        $stdInfNFe = new \stdClass();
        $stdInfNFe->versao = '4.00';
        $stdInfNFe->Id = null;
        $stdInfNFe->pk_nItem = '';
        $infNFe = $nfe->taginfNFe($stdInfNFe);

        $stdIde = new \stdClass();
        $stdIde->cUF = $_ENV['CODIGO_UF'];
        $stdIde->cNF = random_int(10000000, 99999999);
        $stdIde->natOp = textFormat($data['nat_op']);

        $stdIde->mod = $type;
        $stdIde->serie = 1;
        $stdIde->nNF = $data['nNF'];
        $stdIde->dhEmi = date("Y-m-d\TH:i:sP");
        $stdIde->dhSaiEnt = date("Y-m-d\TH:i:sP");
        $stdIde->tpNF = 1; 

        $stdIde->idDest = is_null($destinatario) || $_ENV['UF'] == $destinatario->empresa()->endereco()->uf ? 1 : 2;
		$stdIde->cMunFG = textFormat($_ENV['CODIGO']);
		$stdIde->tpImp = $type == 65 ? 4 : 1;
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

        //EMITENTE
        $stdEmit = new \stdClass();
        $stdEmit->CNPJ = textFormat($_ENV['CNPJ']);
        $stdEmit->xNome = textFormat($_ENV['RAZAO_SOCIAL']);
        $stdEmit->xFant = textFormat($_ENV['NOME_FANTASIA']);
        $stdEmit->IE = textFormat($_ENV['IE']);

        $stdEmit->CRT = 1;

        $emit = $nfe->tagemit($stdEmit);

        $stdEnderEmit = new \stdClass();
        $stdEnderEmit->xLgr = $_ENV['RUA'];
        $stdEnderEmit->nro = $_ENV['NUMERO'];
        $stdEnderEmit->xBairro = $_ENV['BAIRRO'];
        $stdEnderEmit->cMun = $_ENV['CODIGO'];
        $stdEnderEmit->xMun = $_ENV['CIDADE'];
        $stdEnderEmit->UF = $_ENV['UF'];
        $stdEnderEmit->fone = onlyNumbers($_ENV['TELEFONE']);
        $stdEnderEmit->CEP = $_ENV['CEP'];
        $stdEnderEmit->cPais = '1058';
        $stdEnderEmit->xPais = 'BRASIL';

        $enderEmit = $nfe->tagenderEmit($stdEnderEmit);

        //destinatario
        if(!is_null($destinatario)){
            $stdDest = new \stdClass();
            $stdDest->CNPJ = textFormat($destinatario->empresa()->documento);
            $stdDest->xNome = textFormat($destinatario->empresa()->razao_social);
            $stdDest->indIEDest = 1;
            $stdDest->IE = textFormat($destinatario->empresa()->ie_rg);

            $dest = $nfe->tagdest($stdDest);

            $stdEnderDest = new \stdClass();
            $stdEnderDest->xLgr = textFormat($destinatario->empresa()->endereco()->rua);
            $stdEnderDest->nro = textFormat($destinatario->empresa()->endereco()->numero);
            $stdEnderDest->xBairro = textFormat($destinatario->empresa()->endereco()->bairro);
            $stdEnderDest->cMun = textFormat($destinatario->empresa()->endereco()->codigo);
            $stdEnderDest->xMun = textFormat($destinatario->empresa()->endereco()->cidade);
            $stdEnderDest->UF = textFormat($destinatario->empresa()->endereco()->uf);
            $stdEnderDest->fone = onlyNumbers($destinatario->empresa()->telefone);
            $stdEnderDest->CEP = textFormat($destinatario->empresa()->endereco()->cep);
            $stdEnderDest->cPais = '1058';
            $stdEnderDest->xPais = 'BRASIL';

            $enderDest = $nfe->tagenderDest($stdEnderDest);
        }

        foreach($produtos as $key => $prod){
            $stdProd = new \stdClass();
            $stdProd->item = $key+1;
            $stdProd->cEAN = validateEANCode($prod->produto()->codigo) ?? 'SEM GTIN';
            $stdProd->cEANTrib = validateEANCode($prod->produto()->codigo) ?? 'SEM GTIN';
            $stdProd->cProd = $prod->produto()->id;
            $stdProd->xProd = textFormat($prod->produto()->nome);
            $stdProd->NCM = textFormat($prod->produto()->ncm);
            $stdProd->CFOP = textFormat($prod->produto()->cfop);
            $stdProd->uCom = $prod->produto()->tipo;
            $stdProd->qCom = numberFormat($prod->quantidade);
            $stdProd->vUnCom = numberFormat($prod->produto()->preco);
            $stdProd->vProd = numberFormat($prod->produto()->preco * $prod->quantidade);
            $stdProd->uTrib = numberFormat($prod->produto()->tipo);
            $stdProd->qTrib = numberFormat($prod->quantidade);
            $stdProd->vUnTrib = numberFormat($prod->produto()->preco);
            $stdProd->indTot = 1;
            
            $tagProd = $nfe->tagprod($stdProd);

            $stdImposto = new \stdClass();
            $stdImposto->item = $key+1;
            $imposto = $nfe->tagimposto($stdImposto);

            if(in_array($prod->produto()->icms()->codigo, ['101','102','103','201','202','203','300','400','500','900'])){
                $stdICMS = new \stdClass();
                $stdICMS->item = $key+1;
                $stdICMS->orig = 0;
                $stdICMS->CSOSN = $prod->produto()->icms()->codigo;

                $stdICMS->pCredSN = numberFormat($prod->produto()->icms()->tributacao);
                $stdICMS->vCredICMSSN = numberFormat($prod->produto()->icms()->valor); 
                
                $nfe->tagICMSSN($stdICMS);
            }else{
                $stdICMS = new \stdClass();
                $stdICMS->item = $key+1;
                $stdICMS->orig = 0;
                $stdICMS->CST = $prod->produto()->icms()->codigo;
                $stdICMS->modBC = 0;
                $stdICMS->vBC = numberFormat($prod->produto()->icms()->vbc);
                $stdICMS->pICMS = numberFormat($prod->produto()->icms()->tributacao);
                $stdICMS->vICMS = numberFormat($prod->produto()->icms()->valor);

                $nfe->tagICMS($stdICMS);
            }

            // PIS
            $stdPIS = new \stdClass();
            $stdPIS->item = $key + 1;
            $stdPIS->CST = $prod->produto()->pis()->codigo;

            if($stdPIS->CST == '03'){
                $stdPIS->qBCProd = numberFormat($prod->quantidade, 4);
                $stdPIS->vAliqProd = numberFormat($prod->produto()->pis()->tributacao, 4);
                $stdPIS->vPIS = numberFormat($prod->produto()->pis()->valor);
            }else{
                $stdPIS->vBC = numberFormat($prod->produto()->pis()->vbc);
                $stdPIS->pPIS = numberFormat($prod->produto()->pis()->tributacao);
                $stdPIS->vPIS = numberFormat($prod->produto()->pis()->valor);
            }

            $PIS = $nfe->tagPIS($stdPIS);

            // COFINS
            $stdCOFINS = new \stdClass();
            $stdCOFINS->item = $key + 1;
            $stdCOFINS->CST = $prod->produto()->cofins()->codigo;

            if($stdCOFINS->CST == '03'){
                $stdCOFINS->qBCProd = numberFormat($prod->quantidade, 4);
                $stdCOFINS->vAliqProd = numberFormat($prod->produto()->cofins()->tributacao, 4);
                $stdCOFINS->vCOFINS = numberFormat($prod->produto()->cofins()->valor);
            }else{
                $stdCOFINS->vBC = numberFormat($prod->produto()->cofins()->vbc);
                $stdCOFINS->pCOFINS = numberFormat($prod->produto()->cofins()->tributacao);
                $stdCOFINS->vCOFINS = numberFormat($prod->produto()->cofins()->valor);
            }

            $COFINS = $nfe->tagCOFINS($stdCOFINS);

            if($type != 65){
                //IPI
                $std = new \stdClass();
                $std->item = $key+1;
                $std->cEnq = $prod->produto()->ipi()->cEnq ?? '999'; 
                $std->CST = $prod->produto()->ipi()->codigo;
                $std->vBC = numberFormat($prod->produto()->ipi()->vbc);
                $std->pIPI = numberFormat($prod->produto()->ipi()->tributacao);
                $std->vIPI = numberFormat($prod->produto()->ipi()->valor);
                $nfe->tagIPI($std);
            }
        }

        $stdTransp = new \stdClass();
		$stdTransp->modFrete = $data['frete'] ?? '9';

		$transp = $nfe->tagtransp($stdTransp); 

        //TOTALIZADOR NFE
		$stdICMSTot = new \stdClass();
		$stdICMSTot->vProd = 0.00;
		$stdICMSTot->vBC = 0.00;
		$stdICMSTot->vICMS = 0.00;
		$stdICMSTot->vICMSDeson = 0.00;
		$stdICMSTot->vBCST = 0.00;
		$stdICMSTot->vST = 0.00;
		$stdICMSTot->vFrete = 0.00;
		$stdICMSTot->vSeg = 0.00;
		$stdICMSTot->vDesc = 0.00;
		$stdICMSTot->vII = 0.00;
		$stdICMSTot->vIPI = 0.00;
		$stdICMSTot->vPIS = 0.00;
		$stdICMSTot->vCOFINS = 0.00;
		$stdICMSTot->vOutro = 0.00;
		$stdICMSTot->vTotTrib = 0.00;
		$stdICMSTot->vNF = numberFormat($venda->total);

        if($type == 55){
            $stdFat = new \stdClass();
            $stdFat->nFat = (int)$data['nNF'];
            $stdFat->vOrig = numberFormat($venda->total);
            $stdFat->vDesc = numberFormat($venda->desconto);
            $stdFat->vLiq = numberFormat($venda->total);

            if($data['pagamento'] != '90'){
                $fatura = $nfe->tagfat($stdFat);
            }

            //FATURA
            if($data['pagamento'] == '03' || $data['pagamento'] == '04' || $data['pagamento'] == '15'){
                $stdDup = new \stdClass();
                $stdDup->nDup = '001';
                $stdDup->dVenc = date("Y-m-d", strtotime("+".$data['vencimento']." days"));
                $stdDup->vDup = numberFormat($venda->total);

                $nfe->tagdup($stdDup);
            }
        }

        $stdPag = new \stdClass();
        $pag = $nfe->tagpag($stdPag);

        $stdDetPag = new \stdClass();
        $stdDetPag->tPag = $data['pagamento'];
        $stdDetPag->vPag = numberFormat($venda->total);
        // $stdDetPag->indPag = $data['pagamento'] == '01' || $data['pagamento'] == '17' ? 0 : 1; 
        $stdDetPag->vTroco = 0;

        if(in_array($data['pagamento'], ['03', '04'])){
            $stdDetPag->tBand = $data['bandeira'];
            $stdDetPag->tpIntegra = 2;
        }

        $detPag = $nfe->tagdetPag($stdDetPag);

        // AUTORIZADOR XML
        $std = new \stdClass();
        $std->CNPJ = '00980093000168';
        $aut = $nfe->tagautXML($std);
        
        //responsavel tecnico
        $std = new \stdClass();
		$std->CNPJ = $_ENV['CONTACT_DOC']; 
		$std->xContato= 'Suporte AZULI';
		$std->email = $_ENV['CONTACT_EMAIL']; 
		$std->fone = onlyNumbers($_ENV['CONTACT_NUMBER']);
		$nfe->taginfRespTec($std);  
        
        try{
            $nfe->montaNFe();
            
            return [
                'chave' => $nfe->getChave(),
                'xml' => $nfe->getXML()
            ];
        }catch(\Exception $e){
            // LogService::logError('Erro ao gerar XML da NFe', $nfe->getErrors());
            return $nfe->getErrors();
        }
    }

    public function sign(string $xml){
		return $this->tools->signNFe($xml);
	}

    public function transmit($signedXml){
        try{
            $idLote = str_pad(2448, 15, '0', STR_PAD_LEFT);
            $resp = $this->tools->sefazEnviaLote([$signedXml], $idLote, 1);

            $st = new Standardize();
            $std = $st->toStd($resp);

            if($std->cStat != 104){
				return [
					'erro' => "[$std->cStat] - $std->xMotivo"
				];
			}

            if($std->protNFe->infProt->cStat != 100){
                return [
                    'erro' => $std->protNFe->infProt->xMotivo
                ];
            }

            sleep(3);

            $xml = Complements::toAuthorize($signedXml, $resp);
            $xml = $this->xmlService->saveXml($xml, false);

            return [
                'data' => $resp,
                'xml' => $xml
            ];

        }catch(\Exception $e){
            LogService::logError('Erro ao gerar XML da NFe: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function correct(string $nfeKey, string $justification, int $numSeq){
        try{
            $response = $this->tools->sefazCCe($nfeKey, $justification, $numSeq + 1);

            $stdCl = new Standardize($response);
            $std = $stdCl->toStd($response);
            $stdArr = $stdCl->toArray($response);

            if($std->cStat != 128){
                return [
                    'erro' => $stdArr
                ];
            }else{
                $cStat = $std->retEvento->infEvento->cStat;

                if($cStat == '135' || $cStat == '136'){
                    $xml = Complements::toAuthorize($this->tools->lastRequest, $response);
                    $xml = $this->xmlService->saveXml($xml, false);
                    
                    return [
                        'xml' => $xml,
                        'seqEvent' => $numSeq + 1
                    ];
                }else{
                    return [
                        'erro' => 'Não foi possível gerar novo xml'
                    ];
                }
            }

        }catch(\Exception $e){
            LogService::logError('Erro ao gerar carta de correção da NFe: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function cancel(string $chave, string $justification){
        try{
            $respChave = $this->tools->sefazConsultaChave($chave);
            $stdCl = new Standardize($respChave);
            $nProt = $stdCl->toArray()['protNFe']['infProt']['nProt'];

            $response = $this->tools->sefazCancela($chave, $justification, $nProt);

            $stdCl = new Standardize($response);
            $std = $stdCl->toStd();
            $arr = $stdCl->toArray();

            if($std->cStat != 128){
                return [
                    'erro' => $arr
                ];
            }else{
                $cStat = $std->retEvento->infEvento->cStat;

                if($cStat == '101' || $cStat == '135' || $cStat == '155'){
                    $xml = Complements::toAuthorize($this->tools->lastRequest, $response);
                    $xml = $this->xmlService->saveXml($xml, false);
                    
                    return [
                        'xml' => $xml
                    ];
                }else{
                    return [
                        'erro' => 'Não foi possível gerar novo xml do cancelamento'
                    ];
                }
            }
        }catch(\Exception $e){
            LogService::logError('Erro ao gerar cancelamento da NFe: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function transformDanfeToPdf(mixed $xml, string $type = 'danfe'){
        error_reporting(E_ALL & ~E_DEPRECATED);

        try{
            switch($type){
                case 'danfCe':
                    $danfe = new Danfce(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/public/NFe/Xml/'. $xml));
                case 'danfeSimples':
                    $danfe = new DanfeSimples(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/public/NFe/Xml/'. $xml));
                default:
                    $danfe = new Danfe(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/public/NFe/Xml/'. $xml));
            }

            $pdf = $danfe->render();

            return $pdf ?? null;

        }catch(\Exception $e){
            LogService::logError('Erro ao gerar cancelamento da NFe: ' . $e->getMessage());
            return null;
        }
    }

}