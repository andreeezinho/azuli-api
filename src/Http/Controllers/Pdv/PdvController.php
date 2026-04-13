<?php 

namespace App\Http\Controllers\Pdv;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\Venda\VendaTransformer;
use App\Infra\Services\JWT\JWT;
use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Domain\Repositories\Venda\VendaRepositoryInterface;
use App\Domain\Repositories\Produto\ProdutoRepositoryInterface;

class PdvController extends Controller {

    protected $userRepository;
    protected $vendaRepository;
    protected $produtoRepository;

    public function __construct(UserRepositoryInterface $userRepository, VendaRepositoryInterface $vendaRepository, ProdutoRepositoryInterface $produtoRepository){
        $this->userRepository = $userRepository;
        $this->vendaRepository = $vendaRepository;
        $this->produtoRepository = $produtoRepository;
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
                'data' => VendaTransformer::transform($create)
            ]);
        }

        return $this->respJson([
            'message' => 'Venda em andamento',
            'data' => VendaTransformer::transform($lastSale)
        ]);
    }

}