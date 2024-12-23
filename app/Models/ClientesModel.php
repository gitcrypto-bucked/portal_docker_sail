<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\Translation\t;

class ClientesModel extends Model
{
    public function listarClientes()
    {
        return DB::table('clientes')->whereRaw("active ='1'")->get();
    }

    public function getAllClientes()
    {
        /** SELECT c.*, count(u.id) as total from clientes c
        LEFT JOIN users u ON c.cliente =u.cliente
        GROUP BY c.id, u.id */
         return DB::table('clientes')->select(DB::raw(' clientes.*, count(users.id) as total '))
             ->leftJoin('users', 'users.cliente', '=', 'clientes.cliente')
             ->groupByRaw('clientes.id, users.id')
             ->paginate(22);
    }

    public function getFilterCliente($cliente, $onlyActive= false)
    {
        if($onlyActive==false)
        {
            #return DB::table('clientes')->where("cliente","=", $cliente)->get();
            return DB::select('SELECT c.*, count(u.id) as total FROM clientes c
                                     LEFT JOIN users u ON u.cliente = c.id
                                     WHERE c.cliente ="'.$cliente.'" OR c.cliente LIKE "'.$cliente.'%"
                                     GROUP BY c.id, u.id');
        }
        else
        {
            return DB::select('SELECT c.*, count(u.id) as total FROM clientes c
                                     LEFT JOIN users u ON u.cliente = c.id
                                     WHERE c.cliente ="'.$cliente.'" OR c.cliente LIKE "'.$cliente.'%"
                                     AND c.active ="1"
                                     GROUP BY c.id, u.id');
        }

    }

    public function  ativarCliente($clienteID,$logo, $path)
    {
          DB::table('clientes')->where('idCliente', '=', $clienteID)->update(['active'=>1,'logo' => $logo, 'path' => $path]);
         return DB::table('localidades')->where('idCliente',$clienteID)->update(['active' => '1']);
    }

    public function  destivarCliente($clienteID)
    {
       $ok = DB::statement('UPDATE clientes SET active = 0, deactived_at="'.date('Y-m-d H:i:s').'" WHERE idCliente = '.$clienteID);
       $ok= DB::table('localidades')->where('idCliente',$clienteID)->update(['active' => '0']);
       return $ok;
    }

    public function excluirCliente($clienteID)
    {
        return DB::table('clientes')->where('idCliente', '=', $clienteID)->delete();
    }

    public function getClient($cliente)
    {
        return DB::table('clientes')->where("cliente","=", strtolower($cliente))->get();
    }

    public  function getClientUsers($cliente)
    {
        return DB::table('clientes')->select(DB::raw(' users.*'))
            ->join('users', 'users.cliente', '=', 'clientes.id')
            ->where('clientes.cliente','=',$cliente)
            ->groupByRaw('clientes.id, users.id')
            ->paginate(config("pagination.CLIENTS"));
    }

    public function getCLienteNameBiUd($ID)
    {
        return DB::table('clientes')->where('id', $ID)->value('cliente');
    }

    public function updateClienteLogo($clienteID, $logo, $path)
    {
            return DB::table('clientes')->where('idCliente', $clienteID)->update(['logo' => $logo, 'path' => $path]);
    }


    public  function  getLogo($id)
    {
        return DB::table('clientes')->where('id', $id)->get();
    }



}
