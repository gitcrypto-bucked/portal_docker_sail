<?php

namespace App\Http\Controllers;

use App\Models\InventarioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Inventario extends Controller
{
    public function getInventario()
    {
        $model = new InventarioModel();
        $inventario = $model->getInventory(Auth::user()->cliente);
        $localidades = $model->getLocalidades(Auth::user()->cliente);
        $modelo = $model->getModelo(Auth::user()->cliente);
        $serial = $model->getSerial(Auth::user()->cliente);
        $cdc = $model->getCentrosDeCusto(Auth::user()->cliente);
        return view('inventario')->with('inventario', $inventario)->with('localidades', $localidades)->with('modelo', $modelo)->with('serial', $serial)->with('cdc', $cdc);
    }


    public function getInventarioDetails(Request $request)
    {
        $localidade = base64_decode($request->localidade);
        $total = base64_decode($request->total);

        $model = new InventarioModel();
        $modelo = $model->getModeloByLocalidade($localidade);
        $serial = $model->getSerialByLocalidade($localidade);
        $cdc = $model->getCentrosDeCusto(Auth::user()->cliente);
        $inventario =$model->getInventarioDetalhes($localidade, $total);
        return view('inventario_details')->with('inventario', $inventario)->with('modelo', $modelo)->with('serial', $serial)->with('localidade', $localidade)->with('total', $total)->with('cdc', $cdc);;
    }
}
