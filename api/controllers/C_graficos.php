<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use App\Models\graphModel;
use Illuminate\Http\Request;
use Helpers\Helpers;

class C_graficos extends API{

    private $apiModel;
    private $graficosModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->token = $this->apiModel->getToken();
        $this->graficosModel = new graphModel();
    }

    // Total de faturamento
    public function getTotalFaturamento(Request $request){

        if(isset($request->periodo_fim)){
            $request->periodo_fim = "10-22-24";
        }

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $data = $this->graficosModel->getTotalFaturamento($request->idCliente, $request->periodo_fim);

            $st = date('Y-m-d', strtotime(str_replace('-', '/', $data[0]->periodo_inicio)));
            $end = date('Y-m-d', strtotime(str_replace('-', '/', $data[0]->periodo_fim)));
            $dataset = (Helpers::getDatesFromRange($st, $end));
            $total = [0];
            array_push($total,  number_format((float)$data[0]->tot, 2, '.', ''));

            $response = [
                'total' => $total,
                'label' => $dataset
            ];

            // Retornar JSON com status 200
            return response()->json(['total_faturamento' => $response], 200);

        }

    }

    // Total de páginas impressas
    public function  getPaginasMês(Request $request){

        if(isset($request->periodo_fim)){
            $request->periodo_fim = "10-22-24";
        }

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $data =  $this->graficosModel->getTotalPrint($request->idCliente, $request->periodo_fim);
            $total  = [0];
            array_push($total, $data[0]->impresso);

            $response = [
                'paginas' => $total,
                'periodo' => date('M-Y', strtotime(str_replace('-','/', $request->periodo_fim)))
            ];

            // Retornar JSON com status 200
            return response()->json(['total_paginas' => $response], 200);

        }

    }

    // Gráficos de chamados
    public function getChamadosGraph(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            if(isset($request->periodo_inicio)){
                $request->periodo_inicio = "09-23-24";
            }

            if(isset($request->periodo_fim)){
                $request->periodo_fim = "10-22-24";
            }

            $data =  $this->graficosModel->getDataChamados($request->idCliente, $request->periodo_inicio, $request->periodo_fim);
            $dentro = [];
            $fora = [];

            $dataset = [];
            $st = date('Y-m-d', strtotime(str_replace('-', '/', $request->periodo_inicio)));
            $end = date('Y-m-d', strtotime(str_replace('-', '/',$request->periodo_fim)));
            $dataset = (Helpers::getDatesFromRange($st, $end));

            for($i = 0; $i < count($data); $i++){

                array_push($fora, (floatval($data[$i]->FORA)));
                array_push($dentro, floatval($data[$i]->DENTRO));

            }

            $response = [
                'fora'      => $fora,
                'dentro'    => $dentro,
                'datas'     => [
                    date('d/m/Y', strtotime(str_replace('-', '/', $request->periodo_inicio))),
                    date('d/m/Y', strtotime(str_replace('-', '/', $request->periodo_fim)))
                ]
            ];

            // Retornar JSON com status 200
            return response()->json(['total_paginas' => $response], 200);

        }

    }

    // Gráficos de SLA
    public function getSLADentroPercent(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            if(isset($request->periodo_inicio)){
                $request->periodo_inicio = "09-23-24";
            }

            if(isset($request->periodo_fim)){
                $request->periodo_fim = "10-22-24";
            }

            $data =  $this->graficosModel->getSLADentroPercent($request->idCliente, $request->periodo_fim);
            $st = date('M-Y', strtotime(str_replace('-', '/',$request->periodo_inicio)));
            $end = date('M-Y', strtotime(str_replace('-', '/' , $request->periodo_fim)));
            $dataset = (Helpers::getDatesFromRange($st, $end));
            $total = [0];
            $target = [floatval(config('sla.TARGET'))];

            for($i = 0; $i < count($data); $i++){

                array_push($total, $data[$i]->percent  );
                array_push($target, floatval(config('sla.TARGET')));

            }

            $response = [
                'dataset'   => [$st, $end],
                'target'    => $target,
                'total'     => $total
            ];

            // Retornar JSON com status 200
            return response()->json(['sla_dentro_percent' => $response], 200);

        }

    }

}
