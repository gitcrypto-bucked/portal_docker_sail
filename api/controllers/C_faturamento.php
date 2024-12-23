<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use App\Models\InvoiceModel;
use App\Models\graphModel;
use Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

ini_set('memory_limit', '-1');
set_time_limit(0);

class C_faturamento extends API{

    private $apiModel;
    private $faturamentoModel;
    private $graficoModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->token = $this->apiModel->getToken();
        $this->faturamentoModel = new InvoiceModel();
    }

    // Lista todos os faturamentos
    public function getFaturamento(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $response = $this->faturamentoModel->select(Auth::user()->cliente);

            // Retornar JSON com status 200
            return response()->json(['faturamento' => $response], 200);

        }

    }

    public function  getDetailsFaturamento(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $periodo_inicio = base64_decode($request->input('periodo_inicio'));
            $periodo_fim = base64_decode($request->input('periodo_fim'));
            $total = base64_decode($request->input('total'));

            $invoices = $this->faturamentoModel->getDetalhes(Auth::user()->cliente, $periodo_inicio, $periodo_fim, $total);

            $response = [
                'faturamento'    => $invoices,
                'periodo_inicio' => $periodo_inicio,
                'periodo_fim'    => $periodo_fim,
                'total'          => $total
            ];

            // Retornar JSON com status 200
            return response()->json(['faturamento_detalhes' => $response], 200);

        }

    }

    // Gráficos de faturamento
    public function getDashFaturamento(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            // Apenas para teste
            $periodo_fim = '10-22-24';

            // Model grafico
            $this->graficoModel = new graphModel();

            $dataFaturamento = $this->graficoModel->getTotalFaturamento(Auth::user()->cliente);
            $dataPaginas = $this->graficoModel->getTotalPrint(Auth::user()->cliente);

            $st = date('Y-m-d', strtotime(str_replace('-', '/', $dataFaturamento[0]->periodo_inicio)));
            $end = date('Y-m-d', strtotime(str_replace('-', '/',$dataFaturamento[0]->periodo_fim)));
            $dataset = (Helpers::getDatesFromRange($st, $end));

            $totalFaturamento = [0];
            $totalPaginas  = [0];

            array_push($totalFaturamento,  number_format((float)$dataFaturamento[0]->tot, 2, '.', '')  );
            array_push($totalPaginas, $dataPaginas[0]->impresso);

            $fat = ['total'=> $totalFaturamento , "label" => $dataset];
            $pag = ['paginas'=> $totalPaginas, 'periodo'=> date('M-Y', strtotime(str_replace('-','/', $periodo_fim)))];

            $response = [
                'datasets' => $fat['label'],
                'total'    => $fat['total'],
                'paginas'  => $pag['paginas'],
                'periodo'  => $pag['periodo']
            ];

            // Retornar JSON com status 200
            return response()->json(['faturamento_dash' => $response], 200);

        }

    }

    // Envia faturamento
    public function uploadInvoice(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            // Verifica se o arquivo é válido
            if($request->file('ffile')->isValid()){

                $data = null;
                $file = $request->file('ffile');
                $fileExtension = $file->getClientOriginalExtension();

                if(str_contains($file->getClientOriginalName(),'faturamento')!=true){

                    return response()->json(['error' => 'Arquivo inválido!'], 401);

                }

                switch ($fileExtension){

                    case 'csv':
                        $data = [];
                        $file = fopen($request->file('ffile'), "r");

                        while(!feof($file)){
                            $data[] = fgetcsv($file);
                        }

                        fclose($file);
                        event(new \App\Events\ImportInvoiceCSV($data, Auth::user()->email));

                        return response()->json(['success' => 'O Arquivo anexo será processado, enviaremos um e-mail ao terminar!'], 200);

                    break;
                    default :
                        return response()->json(['error' => 'Arquivo inválido!'], 401);

                }

            }

            return response()->json(['error' => 'Arquivo inválido!'], 401);

        }

    }

}
