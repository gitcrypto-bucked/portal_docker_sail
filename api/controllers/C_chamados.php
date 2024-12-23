<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use App\Models\ChamadosModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\graphController;

class C_chamados extends API{

    private $apiModel;
    private $chamadosModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->token = $this->apiModel->getToken();
        $this->chamadosModel = new ChamadosModel();
    }

    // Lista todos os chamados
    public function getChamados(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $response = $this->chamadosModel->getChamadosByClinete(Auth::user()->cliente);

            // Retornar JSON com status 200
            return response()->json(['chamado' => $response], 200);

        }

    }

    // Lista todos os chamados para o dashboard
    public function getDashboardChamados(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            if(empty($request->all())){

                $controller = new graphController();
                $data = $controller->getChamadosGraph(Auth::user()->cliente);
                $sla = $controller->getSLADentroPercent(Auth::user()->cliente);

                $response = [
                    'fora'      => $data[0],
                    'dentro'    => $data[1],
                    'datasets'  => $data[2],
                    'datasets2' => $sla[0],
                    'target'    => $sla[1],
                    'percent'   => $sla[2]
                ];

                // Retornar JSON com status 200
                return response()->json(['chamado' => $response], 200);

            }

        }

    }

    // Upload de chamados
    public function uploadChamados(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            if($request->file('ffile')->isValid()){

                $data = null;
                $file = $request->file('ffile');

                if(!str_contains($file->getClientOriginalName(),'chamados')){

                    return response()->json(['error' => 'Arquivo inválido!'], 401);

                }

                $fileExtension = $file->getClientOriginalExtension();

                switch($fileExtension){

                    case 'csv':
                        $data = [];
                        $file = fopen($request->file('ffile'), "r");

                        while(!feof($file)){

                            $data[]= fgetcsv($file);

                        }

                        fclose($file);
                        event(new \App\Events\ImportChamadosCSV($data, Auth::user()->email));

                        return response()->json(['sucess' => 'O arquivo será processado, enviaremos um e-mail ao terminar!'], 200); exit(2);
                        break;

                    default:
                        return response()->json(['error' => 'Arquivo inválido!'], 401);
                }
            }

            return response()->json(['error' => 'Arquivo inválido!'], 401);

        }

    }

}
