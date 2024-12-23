<?php


namespace Api\Controllers;


use Api\API;

class ApiController extends  API
{

    private $ApiModel;
    private $token = 'KSbt%587ERV1k&457DF%#@KJHB45#&vfdsEYN';

    public function __construct(){
        $this->ApiModel = new ApiModel();
    }

    private function validToken(Request $request){
        return $request->header('Authorization') == $this->token;
    }

}


?>
