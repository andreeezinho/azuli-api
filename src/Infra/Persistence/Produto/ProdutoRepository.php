<?php

namespace App\Infra\Persistence\Produto;

use App\Domain\Models\Produto\Produto;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
use App\Infra\Persistence\BaseRepository;
use App\Infra\Services\Log\LogService;

class ProdutoRepository extends BaseRepository implements ProdutoRepositoryInterface {

    public static $className = Produto::class;

    public function __construct() {
        parent::__construct();
        $this->model = new Produto();
    }

    //TODO: tentar cadastrar tributacao (icms, ipi, pis, cofins) nos produtos
    public function registerProductsFromInvoice(array $products){
        if(isset($products['prod'])){
            $products = $products['prod'];
            $arrPivot['prod'] = $products;
            $products = [];
            $products['prod'] = $arrPivot['prod'];
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
                    $create = $this->create([
                        'nome' => $prod['prod']['xProd'],
                        'codigo' => $prod['prod']['cEAN'] == 'SEM GTIN' ? $prod['prod']['cProd'] : $prod['prod']['cEAN'],
                        'preco' => $prod['prod']['vUnCom'],
                        'estoque' => $prod['prod']['qCom'],
                        'tipo' => $prod['prod']['uCom'],
                        'quant_entrada' => $prod['prod']['qTrib'],
                        'quant_saida' => 1.00,
                        'grupo_produto_id' => 1, //TODO: inserir grupo de produto 
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