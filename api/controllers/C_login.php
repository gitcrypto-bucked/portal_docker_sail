<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use App\Models\LoginModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class C_login extends API{

    private $apiModel;
    private $loginModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->loginModel = new LoginModel();
        $this->token = $this->apiModel->getToken();
    }

    // Login
    public function login(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $remember= false;
            $credentials = [
                'email' => $request->typeEmailX,
                'password' => $request->typePasswordX
            ];

            if($this->loginModel->partnerExists($request->typeUserTokenX)){

                if($request->flexCheckDefault == '0'){
                    $remember = true;
                }

                if(Auth::attempt($credentials, $remember)){

                    if(Auth::user()->active != 1){
                        return response()->json(['error' => 'Usuário não está ativo!'], 401);
                    }else{

                        // Gera o token de acesso único
                        $tokenAuth = hash('sha256', Auth::user()->email);

                        // Obter os dados do usuário autenticado
                        $response = [
                            'token' => $tokenAuth,
                            'usuario' => Auth::user()
                        ];

                        // Retornar JSON com status 200
                        return response()->json(['user' => $response], 200);

                    }

                }
            }

            return response()->json(['error' => 'Código do cliente, usuário ou Senha estão inválidos!'], 401);

        }

    }

}
