<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use App\Models\TrackingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class C_rastreio extends API{

    private $apiModel;
    private $trackingModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->token = $this->apiModel->getToken();
        $this->trackingModel = new TrackingModel();
    }

    // Lista todos os rastreios
    function getTracking(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $response = $this->trackingModel->getTracking(Auth::user()->cliente);

            // Retornar JSON com status 200
            return response()->json(['tracking' => $response], 200);

        }

    }

    // Lista detalhes de um rastreio
    function getTrackingDetails(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $num_pedido = base64_decode($request->numero_pedido);
            return view('tracking_detalhado');

        }

    }

}
