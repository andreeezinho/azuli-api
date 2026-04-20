<?php

namespace App\Http\Controllers\NotaFiscal;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Domain\Repositories\NotaFiscal\NotaFiscalRepositoryInterface;
use App\Domain\Repositories\Empresa\EmpresaRepositoryInterface;
use App\Domain\Repositories\Venda\VendaRepositoryInterface;
use App\Infra\Services\NFe\NFeService;

class NotaFiscalController extends Controller {

    protected $notaFiscalRepository;
    protected $empresaRepository;
    protected $vendaRepository;
    protected $nfeService;

    public function __construct(
        NotaFiscalRepositoryInterface $notaFiscalRepository,
        EmpresaRepositoryInterface $empresaRepository,
        VendaRepositoryInterface $vendaRepository,
        NFeService $nfeService
    ){
        $this->notaFiscalRepository = $notaFiscalRepository;
        $this->empresaRepository = $empresaRepository;
        $this->vendaRepository = $vendaRepository;
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

        $nfe = $this->nfeService->getInvoice($data['chave']);

        if(is_null($nfe)){
            return $this->respJson([
                'message' => 'Não foi possível encontrar NFe através da chave'
            ], 500);
        }


    }

}
