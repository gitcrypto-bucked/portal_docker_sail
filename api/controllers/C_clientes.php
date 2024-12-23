<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use App\Models\ClientesModel;
use Illuminate\Http\Request;

class C_clientes extends API{

    private $apiModel;
    private $clientesModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->token = $this->apiModel->getToken();
        $this->clientesModel = new ClientesModel();
    }

    // Lista todos os clientes
    public function getAllClientes(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $response = $this->clientesModel->getAllClientes();

            // Retornar JSON com status 200
            return response()->json(['cliente' => $response], 200);

        }

    }

    // Lista os usuarios de um determinado cliente
    public  function getUsersFromCliente(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $cliente = base64_decode($request->cliente);
            $users = $this->clientesModel->getClientUsers($cliente);

            $response = [
                'users'     => $users,
                'cliente'   => $cliente
            ];

            // Retornar JSON com status 200
            return response()->json(['users' => $response], 200);

        }

    }

    // Atualiza o logo do cliente
    public function UpdateLogoClientes(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            if($request->file('logo')->isValid()){

                $file = $request->file('logo');
                $path = $request->file('logo')->storeAs('clientes', $file->hashName());

            }

            if($this->clientesModel->updateClienteLogo($request->idCliente, $file->hashName(), $path)){

                return response()->json(['success' => 'Cliente atualizado com sucesso!'], 200);

            }

            return response()->json(['error' => 'Houve um erro ao tentar atualizar o cliente!'], 401);

        }

    }

    // Ativa o cliente
    public function ActiveClientes(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            if($request->file('logo')->isValid()){

                $file = $request->file('logo');
                $path = $request->file('logo')->storeAs('clientes', $file->hashName());

            }

            if($this->clientesModel->ativarCliente($request->idCliente, $file->hashName(), $path)){

                return response()->json(['success' => 'Cliente ativado com sucesso!'], 200);

            }

            return response()->json(['error' => 'Houve um erro ao tentar ativar o cliente!'], 401);

        }

    }

    // Desativa o cliente
    public function DeactiveClientes(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            if($this->clientesModel->destivarCliente($request->idCliente)){

                return response()->json(['success' => 'Cliente deativado com sucesso!'], 200);

            }

            return response()->json(['error' => 'Houve um erro ao tentar desativar o cliente!'], 401);

        }

    }

    // Filtro de clientes
    public function filterClientes(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $filter = strtolower($request->input('filter'));

            if($filter ==''){

                $clients = $this->clientesModel->getAllClientes();
                return response()->json(['error' => 'Não foi possivel concuir a pesquisa!'], 401);

            }

            if($request->flexCheckDefault=='1'){

                $clients = $this->clientesModel->getFilterCliente($filter, true);

            }else{

                $clients = $this->clientesModel->getFilterCliente($filter);

            }

            if(sizeof($clients) >= 0){

                // Retornar JSON com status 200
                return response()->json(['cliente' => $clients], 200);

            }

            $clients = $this->clientesModel->getAllClientes();

            return response()->json(['error' => 'Cliente não encontrado!'], 401);

        }

    }

    // Remove o cliente
    public function RemoveClientes(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            if($this->clientesModel->excluirCliente($request->id)){

                return response()->json(['success' => 'Cliente removido com sucesso!'], 200);

            }

            return response()->json(['error' => 'Houve um erro ao excluir o cliente!'], 401);

        }

    }

}
