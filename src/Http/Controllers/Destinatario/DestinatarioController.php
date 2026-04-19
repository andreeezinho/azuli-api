<?php

namespace App\Http\Controllers\Destinatario;

use App\Http\Controllers\Controller;
use App\Http\Request\Request;
use App\Http\Transformer\Destinatario\DestinatarioTransformer;
use App\Domain\Models\Destinatario\Destinatario;
use App\Domain\Repositories\Destinatario\DestinatarioRepositoryInterface;

class DestinatarioController extends Controller {

    protected $destinatarioRepository;

    public function __construct(DestinatarioRepositoryInterface $destinatarioRepository){
        $this->destinatarioRepository = $destinatarioRepository;
    }

    public function index(Request $request){
        $params = $request->all();

        $destinatarios = $this->destinatarioRepository->all($params);

        return $this->respJson([
            'message' => 'Destinatarios listados',
            'data' => DestinatarioTransformer::transformArray($destinatarios)
        ]);
    }

    public function store(Request $request){
        $data = $request->all();

        $validate = $this->validate($data, [
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'required|string|max:255',
            'documento' => 'required|string|max:18',
            'ie_rg' => 'required|string|max:10',
            'num_serie_nfe' => 'required|int',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $destinatario = $this->destinatarioRepository->create($data);

        if(is_null($destinatario)){
            return $this->respJson([
                'message' => 'Não foi possível cadastrar destinatario'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Cadastro realizado com sucesso',
            'data' => DestinatarioTransformer::transform($destinatario)
        ], 201);
    }

    public function update(Request $request, $uuid){
        $data = $request->all();

        $destinatario = $this->destinatarioRepository->findBy('uuid', $uuid);

        if(is_null($destinatario)){
            return $this->respJson([
                'message' => 'Destinatario não encontrado'
            ], 422);
        }

        $validate = $this->validate($data, [
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'required|string|max:255',
            'documento' => 'required|string|max:18',
            'ie_rg' => 'required|string|max:10',
            'num_serie_nfe' => 'required|int',
            'ativo' => 'max:1'
        ]);

        if(is_null($validate)){
            return $this->respJson([
                'message' => 'Dados inválidos',
                'errors' => $this->getErrors()
            ], 422);
        }

        $destinatario = $this->destinatarioRepository->update($data, $destinatario->id);

        if(is_null($destinatario)){
            return $this->respJson([
                'message' => 'Não foi possível atualizar destinatario'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Sucesso ao atualizar destinatario',
            'data' => DestinatarioTransformer::transform($destinatario)
        ], 201);
    }

    public function destroy(Request $request, $uuid){
        $destinatario = $this->destinatarioRepository->findBy('uuid', $uuid);

        if(is_null($destinatario)){
            return $this->respJson([
                'message' => 'Destinatario não encontrado'
            ], 422);
        }

        $destinatario = $this->destinatarioRepository->delete($destinatario->id);

        if(!$destinatario){
            return $this->respJson([
                'message' => 'Não foi possível deletar destinatario'
            ], 500);
        }

        return $this->respJson([
            'message' => 'Destinatario deletado'
        ], 201);
    }

}