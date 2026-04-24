<?php

namespace App\Infra\Persistence\Produto;

use App\Domain\Models\Produto\Produto;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
use App\Infra\Persistence\BaseRepository;
use App\Infra\Persistence\Tributacao\IcmsRepository;
use App\Infra\Persistence\Tributacao\IpiRepository;
use App\Infra\Persistence\Tributacao\PisRepository;
use App\Infra\Persistence\Tributacao\CofinsRepository;
use App\Infra\Services\Log\LogService;

class ProdutoRepository extends BaseRepository implements ProdutoRepositoryInterface {

    public static $className = Produto::class;
    protected $icmsRepository;
    protected $ipiRepository;
    protected $pisRepository;
    protected $cofinsRepository;

    public function __construct() {
        parent::__construct();
        $this->model = new Produto();
        $this->icmsRepository = new IcmsRepository();
        $this->ipiRepository = new IpiRepository();
        $this->pisRepository = new PisRepository();
        $this->cofinsRepository = new CofinsRepository();
    }

    public function registerProductsFromInvoice(array $products){
        if(isset($products['imposto'])){
            $imposto = $products['imposto'];
        }

        if(isset($products['prod'])){
            $products = $products['prod'];
            $arrPivot['prod'] = $products;
            $products = [];
            $products[0]['prod'] = $arrPivot['prod'];
            $products[0]['imposto'] = $imposto;
        }
                        
        try {
            foreach($products as $prod){
                if(!isset($prod['prod'])){
                    $prod['prod'] = $prod;
                    $arrPivot['prod'] = $prod['prod'];
                    $prod = [];
                    $prod['prod'] = $arrPivot['prod'];
                }

                $findProduct = $this->findBy('codigo', $prod['prod']['cEAN'] == 'SEM GTIN' ? $prod['prod']['cProd'] : $prod['prod']['cEAN']);

                if(is_null($findProduct)){
                    $icms = $prod['imposto']['ICMS'][array_key_first($prod['imposto']['ICMS'])];
                    $ipi = $prod['imposto']['IPI'][array_key_last($prod['imposto']['IPI'])];
                    $pis = $prod['imposto']['PIS'][array_key_first($prod['imposto']['PIS'])];
                    $cofins = $prod['imposto']['COFINS'][array_key_first($prod['imposto']['COFINS'])];

                    $create = $this->create([
                        'nome' => $prod['prod']['xProd'],
                        'codigo' => $prod['prod']['cEAN'] == 'SEM GTIN' ? $prod['prod']['cProd'] : $prod['prod']['cEAN'],
                        'preco' => $prod['prod']['vUnCom'],
                        'estoque' => $prod['prod']['qCom'],
                        'tipo' => $prod['prod']['uCom'],
                        'quant_entrada' => $prod['prod']['qTrib'],
                        'quant_saida' => 1.00,
                        'grupo_produto_id' => 1, //TODO: inserir grupo de produto 
                        'icms_id' => $this->icmsRepository->create([
                            'orig' => $icms['orig'],
                            'tipo' => array_keys($icms)[1],
                            'codigo' => $icms[array_keys($icms)[1]],
                            'tributacao' => $icms['pCredSN'] ?? $icms['pICMS'],
                            'valor' => $icms['vCredICMSSN'] ?? $icms['vICMS'],
                            'vbc' => $icms['vBC'] ?? 0,
                            'ativo' => 1
                        ])->id,
                        'ipi_id' => $this->ipiRepository->create([
                            'cEnq' => $prod['imposto']['IPI']['cEnq'],
                            'codigo' => $ipi['CST'] ?? null,
                            'vbc' => $ipi['vBC'] ?? 0,
                            'tributacao' => $ipi['pIPI'] ?? 0,
                            'valor' => $ipi['vIPI'] ?? 0,
                            'ativo' => 1
                        ])->id,
                        'pis_id' => $this->pisRepository->create([
                            'tipo' => array_keys($prod['imposto']['PIS'])[0] ?? null,
                            'codigo' => $pis['CST'],
                            'tributacao' => $pis['pPIS'] ?? 0,
                            'valor' => $pis['vPIS'] ?? 0,
                            'vbc' => $pis['vBC'] ?? 0,
                            'ativo' => 1
                        ])->id,
                        'cofins_id' => $this->cofinsRepository->create([
                            'tipo' => array_keys($prod['imposto']['COFINS'])[0] ?? null,
                            'codigo' => $cofins['CST'],
                            'tributacao' => $cofins['pCOFINS'] ?? 0,
                            'valor' => $cofins['vCOFINS'] ?? 0,
                            'vbc' => $cofins['vBC'] ?? 0,
                            'ativo' => 1
                        ])->id,
                        'cfop' => (int)$prod['prod']['CFOP'],
                        'ncm' => (int)isset($prod['prod']['NCM']) ?? null,
                        'cest' => (int)isset($prod['prod']['CEST']) ?? null,
                        'ativo' => 1
                    ]); 

                    if(is_null($create)){
                        return null;
                    }

                    continue;
                }
                
                $this->update(['estoque' => ($findProduct->estoque + $prod['prod']['qCom'])], $findProduct->id);
            }

            return true;

        } catch (\Throwable $th) {
            LogService::logError($th->getMessage());
            return null;
        }
    }

}