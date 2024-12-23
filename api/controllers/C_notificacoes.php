<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class C_notificacoes extends API{

    private $apiModel;
    private $notificationModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->token = $this->apiModel->getToken();
        $this->notificationModel = new \App\Models\NotificationModel();
    }

    // Listar todas as notificação
    public function getNotifications(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $response = $this->notificationModel->getAll();

            // Retornar JSON com status 200
            return response()->json(['notificacoes' => $response], 200);

        }

    }

    // Cadastrar notificação
    public function addNewNotification(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            // Verificar se o campo "to" foi preenchido
            if($request->to){
                $request->to = null;
            }

            // Intervalo em dias
            $deve_mostrar_ate = 0;

            switch($request->type){

                case 'news' :

                    $deve_mostrar_ate = intval(Config::get('notification.NEWS'));
                    break;

                case 'system' :

                    $deve_mostrar_ate = intval(Config::get('notification.SYSTEM'));
                    break;

                default :

                    $deve_mostrar_ate = intval(Config::get('notification.DEFAULT'));
                    break;

            }

            $show_till = date('Y-m-d H:i:s', strtotime('+'.intval($deve_mostrar_ate).' days'));

            $dados = [
                'notification'  => $request->text,
                'show_till'     => $show_till,
                'created_at'    => date('Y-m-d H:i:s', time()),
                'active'        => 1,
                'type'          => $request->type,
                'user_email'    => $request->to
            ];

            // Se for inserido
            if($this->notificationModel->insert($dados)){
                return response()->json(['success' => 'Notificação cadastrada com sucesso!'], 200);
            }

            return response()->json(['error' => 'Não foi possível cadastrar a notificação!'], 401);

        }

    }

    // Desabilitar notificação
    public function disableNotification(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $id = $request->id;

            if($id != null || $id != ''){
                return $this->notificationModel->disable($id);
            }else{

                return response()->json(['error' => 'Houve um erro ao desabilitar a notificação!'], 401);

            }


        }

    }

}
