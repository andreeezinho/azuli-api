<?php

namespace App\Http\Controllers\NotaFiscal;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\NotaFiscal\NotaFiscalEntradaTransformer;
use App\Domain\Repositories\NotaFiscal\NotaFiscalRepositoryInterface;
use App\Domain\Repositories\NotaFiscal\NotaFiscalEntradaRepositoryInterface;
use App\Domain\Repositories\Empresa\EmpresaRepositoryInterface;
use App\Domain\Repositories\Venda\VendaRepositoryInterface;
use App\Domain\Repositories\Destinatario\DestinatarioRepositoryInterface;
use App\Domain\Repositories\Emitente\EmitenteRepositoryInterface;
use App\Domain\Repositories\Endereco\EnderecoRepositoryInterface;
use App\Infra\Services\NFe\NFeService;

class NotaFiscalController extends Controller {

    protected $notaFiscalRepository;
    protected $notaFiscalEntradaRepository;
    protected $empresaRepository;
    protected $vendaRepository;
    protected $destinatarioRepository;
    protected $emitenteRepository;
    protected $enderecoRepository;
    protected $nfeService;

    public function __construct(
        NotaFiscalRepositoryInterface $notaFiscalRepository,
        NotaFiscalEntradaRepositoryInterface $notaFiscalEntradaRepository,
        EmpresaRepositoryInterface $empresaRepository,
        VendaRepositoryInterface $vendaRepository,
        DestinatarioRepositoryInterface $destinatarioRepository,
        EmitenteRepositoryInterface $emitenteRepository,
        EnderecoRepositoryInterface $enderecoRepository,
        NFeService $nfeService
    ){
        $this->notaFiscalRepository = $notaFiscalRepository;
        $this->notaFiscalEntradaRepository = $notaFiscalEntradaRepository;
        $this->empresaRepository = $empresaRepository;
        $this->vendaRepository = $vendaRepository;
        $this->destinatarioRepository = $destinatarioRepository;
        $this->emitenteRepository = $emitenteRepository;
        $this->enderecoRepository = $enderecoRepository;
        $this->nfeService = $nfeService;
    }

    // TODO: service do notafiscal com os metodos utilizando sdk do SPED-NFE
    // TODO: documentação demonstrativas para notafiscal

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
                'data' => NotaFiscalEntradaTransformer::transform($findNfe)  
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
                'ie_rg' => $nfe['nfeArray']['NFe']['infNFe']['emit']['IE'],
                'num_serie_nfe' => 1, //TODO: make method $this->lastNFeNumber()
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
            'data_emissao' => $nfe['nfeArray']['NFe']['infNFe']['ide']['dhSaiEnt'],
            'emitentes_id' => $emitente->id,
            'xml_path' => $nfe['xml']
        ]);

        if(is_null($create)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar NFe'
            ], 500);
        }

        return $this->respJson([
            'message' => 'NFe encontrada',
            'data' => NotaFiscalEntradaTransformer::transform($create)
        ], 201);
    }

}
