<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChamadosModel extends Model
{
    public function insert(array $data)
    {
        return DB::table('chamados')->insert($data);
    }


    public function  getChamadosByClinete($clientes)
    {
        return DB::table('chamados')
            ->join('localidades', 'chamados.localidade', '=', 'localidades.localidade')
            ->join('clientes', 'clientes.idCLiente', '=', 'localidades.idCliente')
            ->where('clientes.id', '=', $clientes)
            ->paginate(config('pagination.CHAMADOS'));
    }
}
