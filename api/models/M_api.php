<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

class M_api extends Model{

    // Token API
    public function getToken(){
        return 'KSbt%587ERV1k&457DF%#@KJHB45#&vfdsEYN';
    }

    // Valida Token API
    public function validToken($request, $token){
        return $request->header('Authorization') == $token;
    }

}
