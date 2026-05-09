<?php

namespace App\Http\Controllers\NotaFiscal;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\NotaFiscal\NotaFiscalTransformer;
use App\Http\Transformer\NotaFiscal\NotaFiscalEntradaTransformer;
use App\Domain\Repositories\NotaFiscal\NotaFiscalRepositoryInterface;
use App\Domain\Repositories\NotaFiscal\NotaFiscalEntradaRepositoryInterface;
use App\Domain\Repositories\Empresa\EmpresaRepositoryInterface;
use App\Domain\Repositories\Venda\VendaRepositoryInterface;
use App\Domain\Repositories\Produto\VendaProdutoRepositoryInterface;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
use App\Domain\Repositories\Destinatario\DestinatarioRepositoryInterface;
use App\Domain\Repositories\Emitente\EmitenteRepositoryInterface;
use App\Domain\Repositories\Endereco\EnderecoRepositoryInterface;
use App\Infra\Services\Log\LogService;
use App\Infra\Services\NFe\NFeService;
use App\Infra\Services\Xml\XmlService;

class NotaFiscalController extends Controller {

    protected $notaFiscalRepository;
    protected $notaFiscalEntradaRepository;
    protected $empresaRepository;
    protected $vendaRepository;
    protected $vendaProdutoRepository;
    protected $produtoRepository;
    protected $destinatarioRepository;
    protected $emitenteRepository;
    protected $enderecoRepository;
    protected $nfeService;
    protected $xmlService;
    protected $notaFiscalTransformer;
    protected $notaFiscalEntradaTransformer;

    public function __construct(
        NotaFiscalRepositoryInterface $notaFiscalRepository,
        NotaFiscalEntradaRepositoryInterface $notaFiscalEntradaRepository,
        EmpresaRepositoryInterface $empresaRepository,
        VendaRepositoryInterface $vendaRepository,
        VendaProdutoRepositoryInterface $vendaProdutoRepository,
        ProdutoRepositoryInterface $produtoRepository,
        DestinatarioRepositoryInterface $destinatarioRepository,
        EmitenteRepositoryInterface $emitenteRepository,
        EnderecoRepositoryInterface $enderecoRepository,
        NFeService $nfeService,
        XmlService $xmlService,
        NotaFiscalTransformer $notaFiscalTransformer,
        NotaFiscalEntradaTransformer $notaFiscalEntradaTransformer,
    ){
        parent::__construct();
        $this->notaFiscalRepository = $notaFiscalRepository;
        $this->notaFiscalEntradaRepository = $notaFiscalEntradaRepository;
        $this->empresaRepository = $empresaRepository;
        $this->vendaRepository = $vendaRepository;
        $this->vendaProdutoRepository = $vendaProdutoRepository;
        $this->produtoRepository = $produtoRepository;
        $this->destinatarioRepository = $destinatarioRepository;
        $this->emitenteRepository = $emitenteRepository;
        $this->enderecoRepository = $enderecoRepository;
        $this->nfeService = $nfeService;
        $this->xmlService = $xmlService;
        $this->notaFiscalTransformer = $notaFiscalTransformer;
        $this->notaFiscalEntradaTransformer = $notaFiscalEntradaTransformer;
    }

    public function teste(Request $request){
        return $this->respJson([
            'data' => $this->notaFiscalRepository->getLastNfeNumber()
        ]);
    }

    public function getInvoiceByKey(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'chave' => 'required|string'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $findNfe = $this->notaFiscalEntradaRepository->findBy('chave', $data['chave']);

        if(!is_null($findNfe)){
            return $this->respJson([
                'message' => 'NFe encontrada',
                'data' => $this->notaFiscalEntradaTransformer->transform($findNfe)  
            ], 200);
        }

        $nfe = $this->nfeService->getInvoice($data['chave']);

        if(is_null($nfe)){
            return $this->respJson([
                'message' => 'Não foi possível encontrar NFe através da chave'
            ], 500);
        }

        $empresa = $this->empresaRepository->findBy('documento', $nfe['nfeArray']['NFe']['infNFe']['emit']['CNPJ']);

        if(is_null($empresa)){
            $empresa = $this->empresaRepository->create([
                'razao_social' => $nfe['nfeArray']['NFe']['infNFe']['emit']['xNome'],
                'nome_fantasia' => $nfe['nfeArray']['NFe']['infNFe']['emit']['xFant'],
                'documento' => $nfe['nfeArray']['NFe']['infNFe']['emit']['CNPJ'],
                'telefone' => $nfe['nfeArray']['NFe']['infNFe']['emit']['enderEmit']['fone'],
                'ie_rg' => $nfe['nfeArray']['NFe']['infNFe']['emit']['IE'],
                'num_serie_nfe' => 1, 
                'enderecos_id' => $this->enderecoRepository->create([
                    'cep' => $nfe['nfeArray']['NFe']['infNFe']['emit']['enderEmit']['CEP'],
                    'uf' => $nfe['nfeArray']['NFe']['infNFe']['emit']['enderEmit']['UF'],
                    'codigo' => $nfe['nfeArray']['NFe']['infNFe']['emit']['enderEmit']['cMun'],
                    'cidade' => $nfe['nfeArray']['NFe']['infNFe']['emit']['enderEmit']['xMun'],
                    'rua' => $nfe['nfeArray']['NFe']['infNFe']['emit']['enderEmit']['xLgr'],
                    'bairro' => $nfe['nfeArray']['NFe']['infNFe']['emit']['enderEmit']['xBairro'],
                    'numero' => $nfe['nfeArray']['NFe']['infNFe']['emit']['enderEmit']['nro'],
                    'ativo' => 1,
                ])->id,
                'ativo' => 1
            ]);

            if(is_null($empresa)){
                return $this->respJson([
                    'message' => 'Não foi possível cadastrar empresa emitente'
                ], 500);
            }
        }

        $emitente = $this->emitenteRepository->findBy('empresas_id', $empresa->id);

        if(is_null($emitente)){
            $emitente = $this->emitenteRepository->create([
                'empresas_id' => $empresa->id,
                'ativo' => 1
            ]);
        }

        $create = $this->notaFiscalEntradaRepository->create([
            'chave' => $data['chave'],
            'num_nf' => (int)$nfe['nfeArray']['NFe']['infNFe']['ide']['nNF'],
            'nat_op' => $nfe['nfeArray']['NFe']['infNFe']['ide']['natOp'],
            'gravada' => false,
            'data_emissao' => $nfe['nfeArray']['NFe']['infNFe']['ide']['dhEmi'],
            'emitentes_id' => $emitente->id,
            'total' => (float)$nfe['nfeArray']['NFe']['infNFe']['total']['ICMSTot']['vNF'],
            'xml_path' => $nfe['xml']
        ]);

        if(is_null($create)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar NFe'
            ], 500);
        }

        return $this->respJson([
            'message' => 'NFe encontrada',
            'data' => $this->notaFiscalEntradaTransformer->transform($create)
        ], 201);
    }

    public function registerInvoiceProducts(Request $request, string $uuid){
        $nfe = $this->notaFiscalEntradaRepository->findBy('uuid', $uuid);

        if(is_null($nfe)){
            return $this->respJson([
                'message' => 'Nota fiscal não encontrada'
            ], 422);
        }

        if($nfe->gravada != 0){
            return $this->respJson([
                'message' => 'Os produtos da nota fiscal já foram gravados'
            ], 422);
        }

        $xmlArr = $this->xmlService->convertXmltoArray($nfe->xml_path);

        if(is_null($xmlArr)){
            return $this->respJson([
                'message' => 'Não foi possível converter o xml'
            ], 500);
        }
        
        $registerProducts = $this->produtoRepository->registerProductsFromInvoice($xmlArr['NFe']['infNFe']['det']);
        
        if(is_null($registerProducts)){
            return $this->respJson([
                'message' => 'Não foi possível gravar os produtos da NFe'
            ], 500);
        }

        $update = $this->notaFiscalEntradaRepository->update(['gravada' => 1], $nfe->id);

        if(is_null($update)){
            return $this->respJson([
                'message' => 'Produtos cadastrados mas não foi possível atualizar NFe no sistema'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Produtos gravados com sucesso'
        ], 201);
    }

    public function generateNFe(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'venda_uuid' => 'required',
            'nat_op' => 'required|string',
            'pagamento' => 'required',
            'frete' => 'required'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $venda = $this->vendaRepository->findBy('uuid', $data['venda_uuid']);

        if(is_null($venda)){
            return $this->respJson([
                'message' => 'Venda não encontrada'
            ], 422);
        }

        $produtos = $this->vendaProdutoRepository->findProductsInSale($venda->id);

        $destinatario = isset($data['destinatario_uuid']) ? $this->destinatarioRepository->findBy('uuid', $data['destinatario_uuid']) : null;

        $data = array_merge($data, ['nNF' => $this->notaFiscalRepository->getLastNfeNumber() + 1]);

        try{
            $generate = $this->nfeService
                ->generateXml(
                    $data, 
                    $venda,
                    $produtos,
                    $destinatario,
                    is_null($destinatario) ? 65 : 55
                );
                
            if(is_null($generate)){
                return $this->respJson([
                    'message' => 'Não foi possível gerar XML autenticado' 
                ], 500);
            }

            $transmit = $this->nfeService
                ->transmit(
                    $this->nfeService->sign($generate['xml'])
                );

            if(isset($transmit['erro'])){
                return $this->respJson([
                    'message' => $transmit['erro']
                ], 500);
            }

            $xmlArr = $this->xmlService->convertXmltoArray($transmit['xml']);

            $create = $this->notaFiscalRepository->create([
                'nat_op' => $xmlArr['NFe']['infNFe']['ide']['natOp'],
                'chave' => 
                    str_starts_with($xmlArr['NFe']['infNFe']['@attributes']['Id'], 'NFe') ? 
                    substr($xmlArr['NFe']['infNFe']['@attributes']['Id'], 3) 
                    : $xmlArr['NFe']['infNFe']['@attributes']['Id'],
                'num_nf' => $xmlArr['NFe']['infNFe']['ide']['nNF'],
                'situacao' => $xmlArr['protNFe']['infProt']['xMotivo'],
                'vendas_id' => $venda->id,
                'destinatarios_id' => $destinatario->id ?? null,
                'total' => $xmlArr['NFe']['infNFe']['total']['vNFTot'] ?? $venda->total, 
                'xml_path' => $transmit['xml']
            ]);

            if(is_null($create)){
                return $this->respJson([
                    'message' => 'NFe transmitida, mas não salva no banco de dados',
                    'data' => [
                        'chaveNFe' => 
                            str_starts_with($xmlArr['NFe']['infNFe']['@attributes']['Id'], 'NFe') ? 
                            substr($xmlArr['NFe']['infNFe']['@attributes']['Id'], 3) 
                            : $xmlArr['NFe']['infNFe']['@attributes']['Id'],
                        ]   
                ], 500);
            }

            return $this->respJson([
                'message' => 'NFe Transmitida com sucesso',
                'data' => $this->notaFiscalTransformer->transform($create)
            ], 201);
        }catch(\Exception $e){
            LogService::logError($e->getMessage());
            return $this->respJson([
                'message' => 'Erro ao processar transmissão da NFe'
            ], 500);
        }
    }

}
