<?php

namespace App\Http\Controllers;

use App\Models\ClientesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserModel;

class UserController extends Controller
{

    public function newUser(Request $request)
    {
        $cliente = $request->cliente;
        return view('add_user')->with('cliente',$cliente);
    }

    //salva dados de cadastro de usuario, envia e-mail para o mesmo cadastrar a senha
    public function createNewUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'confirm_email'=> 'required|same:email',
            'empresa'=>'required',
            'centrocusto'=>'required',
            'level'=>'required'
        ]);
        $model = new ClientesModel();

        $client= $model->getClient($request->empresa)[0];
        $user_token = $client->token;
        $clientID =$client->id;
        unset($model);

        if($request->email!=$request->confirm_email)
        {
            return redirect('/new_user?cliente='.$request->empresa)->with('error', 'O email não coincide com o de confirmação.');
        }
        $created_at= date('Y-m-d H:i:s', time());

        $model = new UserModel();
        $model->addUser(
            $request->name,
            $request->email,
            $request->level,
            $created_at,
            $clientID,
            $request->centrocusto);


        $token = md5(bin2hex(random_bytes(32))); // token de acesso para usuario cadastrar

        $model->createPasswordReset($request->email, $token, $created_at);

        $createUserPassWordURL =route("user-token",$token);

        event(new \App\Events\RegistredUser(['name'=>$request->name, 'email'=>$request->email, 'user_token'=> $user_token, 'url'=>$createUserPassWordURL]));
        #dd($user_token);
        return redirect('/new_user?cliente='.$request->empresa)->with('success', 'Cadastro Realizado com Sucesso!');

    }



    //usuario cadastrado, valida se o token de acesso é valido e envia para pagina de alterar senha
    public function checkUserToken(Request $request)
    {
        $token = $request->token;
        $model = new UserModel();
        $user =$model->getUserAndTokenValid($token);

        if(isset($user[0]) && !empty($user[0]))
        {
            return view("password-update",['user' =>  $user, 'token'=>$request->token]); //envia para view o usuario alterar a senha
        }
        if(!isset($user[0]) && empty($user[0]))
        {
            return redirect('/expired_link');  // o usuario ja alterou a senha ou token expirou
        }
    }

    //usuario cadastrado, permite cadastrar senha de acesso via link
    //funciona no fluxo de recuperar senha
    public function registerUserPassword(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email',
            'company'=>'required',
            'password' => 'required|min:8',
            'passwordConfirmation' => 'min:8',
        ]);

        if(strtolower($request->password )!= strtolower($request->passwordConfirmation))
        {
            return view("password-update")->with('error', 'A senha e confirmação de senha não coincidem!');
        }
        DB::table('users')->where('email', $request->email)->update(['empresa'=>$request->empresa, 'password'=> Hash::make($request->senha),]);
        DB::table('password_reset_tokens')->where('token','=',$request->token)->delete();

        return redirect('/login')->with('success', 'Senha Cadastrada com Sucesso!');
    }


    // Usuario cadastrado e logado Permite alterar sua senha
    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|unique:users',
            'senha' => 'required|min:8',
            'confsenha' => 'min:8',
        ]);

        User::updated([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
        ]);
        return redirect('/user-profile')->with('success', 'Dados atualizados com sucesso.');

    }

    /**fluxo para recuperação de senha, */
    /**verifica se usuario está cadastrado e ativo*/

    public function recoverPassword(Request $request)
    {
        $request->validate([
            'typeEmailX' => 'required|email'
        ]);
        $email = $request->typeEmailX;
        $model = new UserModel();
        $user = $model->getUserByEmail($email)[0];
        if($user->partner!='1')
        {
            return redirect('/forgot-password')->with('error', 'Usuario invalido!'); //parceiro desabilitado
        }
        $token = md5(bin2hex(random_bytes(32))); // token de acesso para usuario cadastrar

        $model->createPasswordReset($user->email, $token, date('Y-m-d H:i:s'));

        $createUserPassWordURL =route("user-token",$token);

        event(new \App\Events\UserRecovered(['name'=>$user->name, 'email'=>$user->email, 'user_token'=>$user->user_token, 'url'=>$createUserPassWordURL]));

        return redirect('/forgot-password')->with('success', 'Você receberá em instantes, um link no seu email cadastrado!');

    }

    public function forgotPassword():string
    {
        return view('password-recover');
    }


    public function filterUsers(Request $request)
    {
        $model = new UserModel();

        $cliente = $request->cliente;
        $filter = strtolower($request->input('filter'));
        if($filter =='')
        {
            $users = $model->getClientUsers($filter,$cliente);
            return view('cliente_users')->with('users', $users)->with('cliente', $cliente)->with('error', "Não foi possivel concuir a pesquisa");
        }
        if($request->flexCheckDefault=='1')
        {
            $users = $model->getFilterClienteUser($filter,$cliente,true);
        }
        else
        {
            $users = $model->getFilterClienteUser($filter,$cliente,false);

        }
        if(sizeof($users) >= 0)
        {
            return view('cliente_users')->with('users', $users)->with('cliente', $cliente);
        }
        $users = $model->getClientUsers($cliente);
        return view('cliente_users')->with('users', $users)->with('cliente', $cliente);
    }


    public function redirectView($clients, $error =null)
    {
        if($error != null)
        {
            return view('usuarios_clientes')->with('error', $error)->with('clients', $clients);
        }
        return view('usuarios_clientes')->with('clients', $clients);
    }



}
