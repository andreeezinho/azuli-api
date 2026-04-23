<?php

namespace App\Infra\Persistence\Produto;

use App\Domain\Models\Produto\Produto;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
use App\Infra\Persistence\BaseRepository;
use App\Infra\Persistence\Tributacao\IcmsRepository;
use App\Infra\Services\Log\LogService;

class ProdutoRepository extends BaseRepository implements ProdutoRepositoryInterface {

    public static $className = Produto::class;
    protected $icmsRepository;

    public function __construct() {
        parent::__construct();
        $this->model = new Produto();
        $this->icmsRepository = new IcmsRepository();
    }

    //TODO: tentar cadastrar tributacao (icms, ipi, pis, cofins) nos produtos
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