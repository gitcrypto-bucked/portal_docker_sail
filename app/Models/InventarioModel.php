<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventarioModel extends Model
{
    public function  getInventory($cliente)
    {
        return DB::table('clientes')
            ->selectRaw('localidades.localidade ,COUNT(equipamentos.id) AS quantidade, clientes.cliente')->distinct('equipamentos.idserial')
            ->leftJoin('localidades','localidades.idCliente', '=', 'clientes.idCliente')
            ->leftJoin('equipamentos', 'equipamentos.idLocalidade','=','localidades.idLocalidade')
            ->where('clientes.id', '=', $cliente)
            ->groupByRaw('localidades.localidade, clientes.cliente')
            ->paginate(config('pagination.INVENTORIES'));

    }


    public function getLocalidades($cliente)
    {
        return DB::select('SELECT DISTINCT c.cliente, l.localidade , count(e.id) as total FROM clientes c
                LEFT JOIN localidades l ON l.idCliente = c.idCliente
                LEFT JOIN equipamentos e ON e.idLocalidade = l.idLocalidade
                WHERE c.id="'.$cliente.'"
                GROUP BY c.cliente, l.localidade
                ');
    }

    public function  getModelo($cliente)
    {

        return DB::select("SELECT distinct equipamentos.modelo
                                    FROM equipamentos
                                    WHERE exists
                                    (
                                        SELECT  * FROM localidades l
                                        JOIN equipamentos e ON l.idLocalidade = e.idLocalidade
                                        JOIN clientes c ON l.idCliente = c.idCliente
                                        WHERE c.id=".$cliente.")
                                ");
    }

    public function  getSerial($cliente)
    {
        return DB::select("SELECT distinct equipamentos.serial
                                    FROM equipamentos
                                    WHERE exists
                                    (
                                        SELECT  * FROM localidades l
                                        JOIN equipamentos e ON l.idLocalidade = e.idLocalidade
                                        JOIN clientes c ON l.idCliente = c.idCliente
                                        WHERE c.id=".$cliente.")
                                ");
    }

    public function  getCentrosDeCusto($cliente)
    {

    }

    public function  getModeloByLocalidade($localidade)
    {
        return DB::select("SELECT distinct equipamentos.modelo
                                    FROM equipamentos
                                    WHERE exists
                                    (
                                        SELECT  * FROM localidades l
                                        JOIN equipamentos e ON l.idLocalidade = e.idLocalidade
                                        JOIN clientes c ON l.idCliente = c.idCliente
                                        WHERE l.localidade='".$localidade."')
                                ");
    }

    public function  getSerialByLocalidade($localidade)
    {
        return DB::select("SELECT  e.id , e.serial FROM clientes c
                    LEFT JOIN localidades l ON c.idCliente = l.idCliente
                    LEFT JOIN equipamentos e ON l.idLocalidade = e.idLocalidade
                   WHERE l.localidade='".$localidade."'  AND e.modelo!=''");
    }

    public function  getInventarioDetalhes( $localidade, $total)
    {
        return DB::table('equipamentos')
            ->join('localidades','localidades.idLocalidade','=','equipamentos.idLocalidade')
            ->join('clientes','clientes.idCliente','=','localidades.idCliente')
            ->groupByRaw('equipamentos.idLocalidade, equipamentos.id,equipamentos.serial , localidades.id')
            ->where('localidades.localidade', '=', $localidade)
            ->paginate(config('pagination.DETAILS'));
    }
}
