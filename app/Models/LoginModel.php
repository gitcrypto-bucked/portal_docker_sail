<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginModel extends Model
{

    public function partnerExists($token)
    {
        return  DB::table('clientes')->select(DB::raw(' users.*'))
        ->join('users', 'users.cliente', '=', 'clientes.id')
        ->where('clientes.token','=',$token)->where('clientes.active','=','1')
        ->groupByRaw('clientes.id, users.id')
        ->exists();
    }
}
