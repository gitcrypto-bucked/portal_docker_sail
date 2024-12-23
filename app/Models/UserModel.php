<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserModel extends Model
{
    public function addUser($name, $email, $level,  $created_at, $empresa, $centrocusto):bool
    {
       return DB::table('users')->
       insert(['name'=>$name,
            'email'=>$email,
            'password'=> md5(time()),
            'active'=>'1',
            'level' => $level,
            'created_at'=>$created_at,
            'cliente' => $empresa,
            'cost_center' => strtolower($centrocusto)   ,
            'last_activity'=>$created_at
        ]);
    }


    public function tokenExists($token):bool
    {
        return DB::table('users')->where('user_token', $token,)->exists();
    }


    public function createPasswordReset($email,  $token,$created_at):bool
    {
        return  DB::table('password_reset_tokens')->insert(['email'=>$email,'token'=>$token,'created_at'=>$created_at ]); //tabela do token de usuario
    }

    public function getUserAndTokenValid($token)
    {
        return   DB::table('users')
            ->select(['users.name', 'users.email', 'users.company'])
            ->join('password_reset_tokens', 'users.email', '=', 'password_reset_tokens.email')
            ->join('lowcost_partner','lowcost_partner.company','=','users.company')
            ->where('password_reset_tokens.token', '=',$token)->where('lowcost_partner.active','=','1')
            ->get();
    }


    public function getUserByEmail($email)
    {
        return   DB::table('users')
            ->select(['users.name', 'users.email', 'users.company' , 'users.user_token', 'lowcost_partner.active as partner','lowcost_partner.company'])
            ->join('lowcost_partner', 'users.company', '=', 'lowcost_partner.company')
            ->where('users.email', '=',$email)->where('users.active','=','1')
            ->get();
    }


    public function getFilterClienteUser($user, $cliente, $onlyActive= false)
    {
        if ($onlyActive == false)
        {
            $SQL= "SELECT u.* FROM clientes c
            INNER JOIN users u ON c.cliente=u.cliente
            WHERE c.cliente='".$cliente."' AND u.name LIKE '".$user."%' OR u.email LIKE '".$user."%'";
            return DB::select($SQL);
        } else
        {
            $SQL= "SELECT u.* FROM clientes c
            INNER JOIN users u ON c.cliente=u.cliente
            WHERE c.cliente='".$cliente."' AND u.name LIKE '".$user."%' OR u.email LIKE '".$user."%' AND u.active='1'";

            return DB::select($SQL);
        }
    }

    public  function getClientUsers($cliente)
    {
        return DB::table('clientes')->select(DB::raw(' users.*'))
            ->join('users', 'users.cliente', '=', 'clientes.cliente')
            ->where('clientes.cliente','=',$cliente)
            ->groupByRaw('clientes.id, users.id')
            ->paginate(config("pagination.CLIENTS"));
    }




}
