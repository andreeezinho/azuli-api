<?php

namespace App\Http\Controllers\Produto;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
// use App\Http\Transformer\Produto\ProdutoTransformer;

class ProdutoController extends Controller {

    protected $produtoRepository;

    public function __construct(ProdutoRepositoryInterface $produtoRepository){
        parent::__construct();
        $this->produtoRepository = $produtoRepository;
    }

}
