<?php 

namespace App\Http\Controllers\Pdv;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\Venda\VendaTransformer;
use App\Infra\Services\JWT\JWT;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Domain\Repositories\Venda\VendaRepositoryInterface;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;
use App\Domain\Repositories\Produto\VendaProdutoRepositoryInterface;
use App\Http\Transformer\Produto\VendaProdutoTransformer;

class PdvController extends Controller {

    protected $userRepository;
    protected $vendaRepository;
    protected $produtoRepository;
    protected $vendaProdutoRepository;

    public function __construct(UserRepositoryInterface $userRepository, VendaRepositoryInterface $vendaRepository, ProdutoRepositoryInterface $produtoRepository, VendaProdutoRepositoryInterface $vendaProdutoRepository){
        $this->userRepository = $userRepository;
        $this->vendaRepository = $vendaRepository;
        $this->produtoRepository = $produtoRepository;
        $this->vendaProdutoRepository = $vendaProdutoRepository;
    }

    public function index(Request $request){
        $user = $this->userRepository
            ->findBy(
                'uuid', 
                JWT::validateToken($request->getHeaders('Authorization'))['uuid']
            );

        $lastSale = $this->vendaRepository->findLastUserSale($user->id);

        if(is_null($lastSale)){
            $create = $this->vendaRepository->create(['usuarios_id' => $user->id]);

            if(is_null($create)){
                return $this->respJson([
                    'message' => 'Não foi possível gerar nova venda'
                ], 422);
            }

            return $this->respJson([
                'message' => 'Nova venda em aberto',
                'data' => [
                    'venda' => VendaTransformer::transform($create),
                    'produtos' => VendaProdutoTransformer::transformArray($this->vendaProdutoRepository->findProductsInSale($create->id))
                ]
            ]);
        }

        return $this->respJson([
            'message' => 'Venda em andamento',
            'data' => [
                'venda' => VendaTransformer::transform($lastSale),
                'produtos' => VendaProdutoTransformer::transformArray($this->vendaProdutoRepository->findProductsInSale($lastSale->id))
            ]
        ]);
    }

    public function addProductInSale(Request $request){
        $data = $request->all();

        $venda = $this->vendaRepository->findBy('uuid', $data['venda_uuid']);

        if(is_null($venda)){
            return $this->respJson([
                'message' => 'Venda não encontrada'
            ], 422);
        }

        $produto = $this->produtoRepository->findBy('uuid', $data['produto_uuid']);

        if(is_null($produto)){
            return $this->respJson([
                'message' => 'Produto não encontrado'
            ], 422);
        }

        $data = array_merge(['vendas_id' => $venda->id, 'produtos_id' => $produto->id], $data);

        $vendaProduto = $this->vendaProdutoRepository->create($data);

        if(is_null($vendaProduto)){
            return $this->respJson([
                'message' => 'Não foi possível adicionar o produto'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Produto adicionado com sucesso'
        ], 201);
    }

    public function updateProductInSale(Request $request){
        $data = $request->all();

        $vendaProduto = $this->vendaProdutoRepository->findBy('uuid', $data['uuid']);

        if(is_null($vendaProduto)){
            return $this->respJson([
                'message' => 'Não foi possível encontrar produto na venda'
            ], 422);
        }

        $vendaProduto = $this->vendaProdutoRepository->update(["quantidade" => $vendaProduto->quantidade + $data['quantidade']], $vendaProduto->id);

        if(!$vendaProduto){
            return $this->respJson([
                'message' => 'Não foi possível atualizar o produto'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Produto atualizado'
        ], 201);
    }

    public function removeProductInSale(Request $request){
        $data = $request->all();

        $vendaProduto = $this->vendaProdutoRepository->findBy('uuid', $data['uuid']);

        if(is_null($vendaProduto)){
            return $this->respJson([
                'message' => 'Não foi possível encontrar produto na venda'
            ], 422);
        }

        $vendaProduto = $this->vendaProdutoRepository->delete($vendaProduto->id);

        if(!$vendaProduto){
            return $this->respJson([
                'message' => 'Não foi possível remover o produto'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Produto removido da venda'
        ], 201);

    }

}