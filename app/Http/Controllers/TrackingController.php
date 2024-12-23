<?php

namespace App\Http\Controllers;

use App\Models\TrackingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    function getTracking(Request $request)
    {
        $model = new TrackingModel();
        $track = $model->getTracking(Auth::user()->cliente);
        return view('tracking')->with('track', $track);
    }


    function getTrackingDetails(Request $request)
    {
        $model = new TrackingModel();
        $num_pedido = base64_decode($request->numero_pedido);
        return view('tracking_detalhado')->with('pedido', $model->getTrackingDetails($num_pedido));
    }
}
