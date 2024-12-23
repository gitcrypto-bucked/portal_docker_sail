<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use App\Models\UserModel;
use App\Models\ClientesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class C_usuarios extends API{

    private $apiModel;
    private $clientesModel;
    private $usuariosModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->token = $this->apiModel->getToken();
        $this->clientesModel = new ClientesModel();
        $this->usuariosModel = new userModel();
    }

    // salva dados de cadastro de usuario
    // envia e-mail para o mesmo cadastrar a senha
    public function createNewUser(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $request->validate([
                'name'          => 'required',
                'email'         => 'required|email|unique:users',
                'confirm_email' => 'required|same:email',
                'empresa'       => 'required',
                'centrocusto'   => 'required',
                'level'         => 'required'
            ]);


            $client = $this->clientesModel->getClient($request->empresa)[0];
            $user_token = $client->token;
            $clientID = $client->id;
            unset($this->clientesModel);

            if($request->email != $request->confirm_email){
                return response()->json(['error' => 'O email não coincide com a confirmação!'], 401);
            }

            $created_at = date('Y-m-d H:i:s', time());

            $this->usuariosModel->addUser(
                $request->name,
                $request->email,
                $request->level,
                $created_at,
                $clientID,
                $request->centrocusto
            );

            // Token de acesso para usuario cadastrar
            $token = md5(bin2hex(random_bytes(32)));

            $this->usuariosModel->createPasswordReset($request->email, $token, $created_at);

            $createUserPassWordURL = route("user-token", $token);

            event(new \App\Events\RegistredUser(['name' => $request->name, 'email' => $request->email, 'user_token' => $user_token, 'url'=>$createUserPassWordURL]));

            return response()->json(['success' => 'Cadastro realizado com sucesso!'], 200);

        }

    }

    //usuario cadastrado, valida se o token de acesso é valido e envia para pagina de alterar senha
    public function checkUserToken(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $token = $request->token;
            $user = $this->usuariosModel->getUserAndTokenValid($token);

            if(isset($user[0]) && !empty($user[0])){

                $response = [
                    'user'  => $user,
                    'token' => $request->token
                ];

                // Retornar JSON com status 200
                return response()->json(['usuario' => $response], 200);

            }

            if(!isset($user[0]) && empty($user[0])){

                return response()->json(['error' => 'Token expirou ou usuário alterou a senha!'], 401);

            }

        }

    }

    // Usuario cadastrado, permite cadastrar senha de acesso via link
    // Funciona no fluxo de recuperar senha
    public function registerUserPassword(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $request->validate([
                'name' => 'required',
                'email' => 'email',
                'company'=>'required',
                'password' => 'required|min:8',
                'passwordConfirmation' => 'min:8',
            ]);

            if(strtolower($request->password )!= strtolower($request->passwordConfirmation)){

                return response()->json(['error' => 'Senhas não coincidem!'], 401);

            }

            DB::table('users')->where('email', $request->email)->update(['empresa'=>$request->empresa, 'password'=> Hash::make($request->senha),]);
            DB::table('password_reset_tokens')->where('token','=',$request->token)->delete();

            return response()->json(['success' => 'Senha cadastrada com sucesso!'], 200);

        }

    }

    // Usuario cadastrado e logado Permite alterar sua senha
    public function updateUser(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $request->validate([
                'name'      => 'required',
                'email'     => 'email|unique:users',
                'senha'     => 'required|min:8',
                'confsenha' => 'min:8'
            ]);

            User::updated([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->senha),
            ]);

            return response()->json(['success' => 'Dados atualizados com sucesso!'], 200);

        }

    }

    /**fluxo para recuperação de senha, */
    /**verifica se usuario está cadastrado e ativo*/

    public function recoverPassword(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $request->validate([
                'typeEmailX' => 'required|email'
            ]);

            $email = $request->typeEmailX;

            $user = $this->usuariosModel->getUserByEmail($email)[0];

            if($user->partner != '1'){

                return response()->json(['error' => 'Usuário não existe!'], 401);
            }

            $token = md5(bin2hex(random_bytes(32))); // token de acesso para usuario cadastrar

            $this->usuariosModel->createPasswordReset($user->email, $token, date('Y-m-d H:i:s'));

            $createUserPassWordURL =route("user-token",$token);

            event(new \App\Events\UserRecovered(['name' => $user->name, 'email' => $user->email, 'user_token'=>$user->user_token, 'url'=>$createUserPassWordURL]));

            return response()->json(['success' => 'Email de recuperação enviado com sucesso!'], 200);

        }

    }

    // Busca os usuarios de acordo com o filtro
    public function filterUsers(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $cliente = $request->cliente;
            $filter = strtolower($request->input('filter'));

            if($filter == ''){

                $users = $this->usuariosModel->getClientUsers($filter, $cliente);

                $response = [
                    'users'     => $users,
                    'cliente'   => $cliente,
                    'error'     => "Não foi possivel concuir a pesquisa"
                ];

                // Retornar JSON com status 200
                return response()->json(['error' => $response], 200);

            }

            if($request->flexCheckDefault == '1'){

                $users = $this->usuariosModel->getFilterClienteUser($filter, $cliente, true);

            }else{

                $users = $this->usuariosModel->getFilterClienteUser($filter, $cliente, false);

            }

            if(sizeof($users) >= 0){

                return view('cliente_users')->with('users', $users)->with('cliente', $cliente);

            }

            $users = $this->usuariosModel->getClientUsers($cliente);

            $response = [
                'users'     => $users,
                'cliente'   => $cliente
            ];

            // Retornar JSON com status 200
            return response()->json(['error' => $response], 200);

        }

    }

}
