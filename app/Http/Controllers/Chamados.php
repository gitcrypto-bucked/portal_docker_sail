<?php

namespace App\Http\Controllers;

use App\Models\ChamadosModel;
use App\Models\ClientesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\graphController;

class Chamados extends Controller
{
    public function index()
    {
        return view('chamados-upload');
    }

    public function uploadChamados(Request $request)
    {
        if ($request->file('ffile')->isValid())
        {
            $data = null;
            $file = $request->file('ffile');
            if(str_contains($file->getClientOriginalName(),'chamados')!=true)
            {
                return redirect('/chamados-upload')->with('error','Arquivo invalido');
            }
            $fileExtension = $file->getClientOriginalExtension();
            switch ($fileExtension)
            {
                case 'csv':
                    $data = [];
                    $file = fopen($request->file('ffile'), "r");
                    while(! feof($file))
                    {
                        $data[]= fgetcsv($file);
                    }
                    fclose($file);
                    event(new \App\Events\ImportChamadosCSV($data, Auth::user()->email));
                    return redirect('/invoice-upload')->with('sucess','O Arquivo anexo será processado, enviaremos um e-mail ao terminar.'); exit(2);
                    break;
                default:     return redirect('/chamados-upload')->with('error','Arquivo invalido');
            }
        }
        return redirect('/chamados-upload')->with('error','Arquivo invalido');

    }



    public function getChamados(Request $request)
    {
        $model = new ChamadosModel();
        $chamados = $model->getChamadosByClinete(Auth::user()->cliente);
        return view('chamados')->with('chamados', $chamados);
    }


    public function getDashboardChamados(Request $request)
    {
            if(empty($request->all()))
            {
                $controller = new graphController();
                $data = $controller->getChamadosGraph(Auth::user()->cliente);
                $sla = $controller->getSLADentroPercent(Auth::user()->cliente);
                return view('dashboard_chamados')->with('fora',$data[0])->with('dentro',$data[1])->with('datasets',$data[2])->with('datasets2',$sla[0])->with('target', $sla[1])->with('percent',$sla[2]);
            }
    }

}
