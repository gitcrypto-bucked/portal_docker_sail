<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use App\Models\InventarioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class C_inventario extends API{

    private $apiModel;
    private $inventarioModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->token = $this->apiModel->getToken();
        $this->inventarioModel = new InventarioModel();
    }

    // Lista todos os inventários
    public function getInventario(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $response = [
                'inventario'    => $this->inventarioModel->getInventory(Auth::user()->cliente),
                'localidades'   => $this->inventarioModel->getLocalidades(Auth::user()->cliente),
                'modelo'        => $this->inventarioModel->getModelo(Auth::user()->cliente),
                'serial'        => $this->inventarioModel->getSerial(Auth::user()->cliente),
                'cdc'           => $this->inventarioModel->getCentrosDeCusto(Auth::user()->cliente)
            ];

            // Retornar JSON com status 200
            return response()->json(['inventario' => $response], 200);

        }

    }

    // Lista detalhes de um inventário
    public function getInventarioDetails(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $localidade = base64_decode($request->localidade);
            $total = base64_decode($request->total);

            $response = [
                'modelo'     => $this->inventarioModel->getModeloByLocalidade($localidade),
                'serial'     => $this->inventarioModel->getSerialByLocalidade($localidade),
                'cdc'        => $this->inventarioModel->getCentrosDeCusto(Auth::user()->cliente),
                'inventario' => $this->inventarioModel->getInventarioDetalhes($localidade, $total)
            ];

            // Retornar JSON com status 200
            return response()->json(['inventario_detalhes' => $response], 200);

        }

    }

}
