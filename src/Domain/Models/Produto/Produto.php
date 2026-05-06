<?php

namespace App\Domain\Models\Produto;

use App\Domain\Models\Traits\ModelTrait;
use App\Infra\Persistence\GrupoProduto\GrupoProdutoRepository;
use App\Infra\Persistence\Tributacao\CofinsRepository;
use App\Infra\Persistence\Tributacao\IcmsRepository;
use App\Infra\Persistence\Tributacao\IpiRepository;
use App\Infra\Persistence\Tributacao\PisRepository;

class Produto {

    use ModelTrait;

    public const TABLE = 'produtos';

    public int $id;
    public ?string $uuid;
    public string $nome;
    public string $codigo;
    public float $preco;
    public float $estoque;
    public string $tipo;
    public float $quant_entrada;
    public float $quant_saida;
    public int $grupo_produto_id;
    public ?int $icms_id;
    public ?int $ipi_id;
    public ?int $pis_id;
    public ?int $cofins_id;
    public int $cfop;
    public ?int $ncm;
    public ?int $cest;
    public int $ativo;
    public ?string $created_at;
    public ?string $updated_at;

    public function grupoProduto(){
        return $this->belongsTo(GrupoProdutoRepository::class, $this->grupo_produto_id);
    }

    public function icms(){
        return $this->belongsTo(IcmsRepository::class, $this->icms_id);
    }

    public function ipi(){
        return $this->belongsTo(IpiRepository::class, $this->icms_id);
    }

    public function pis(){
        return $this->belongsTo(PisRepository::class, $this->icms_id);
    }

    public function cofins(){
        return $this->belongsTo(CofinsRepository::class, $this->icms_id);
    }

    public function create(array $data) : Produto {
        $produto = new Produto();
        $produto->setFields($data);
        $produto->uuid = $data['uuid'] ?? $this->generateUUID();
        return $produto;
    }

}