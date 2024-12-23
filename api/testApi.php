<?php


namespace Api;

use Api\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class testApi extends API
{

    public function test(Request $request)
    {
       parent::baererToken($request);
       $id = $request->id;
       echo $id;
       exit;
     }
}
