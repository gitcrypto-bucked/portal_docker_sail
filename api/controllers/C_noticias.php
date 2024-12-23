<?php

namespace Api\Controllers;

use Api\API;
use Api\Models\M_api;
use App\Models\NewsModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class C_noticias extends API{

    private $apiModel;
    private $noticiasModel;
    private $token;

    public function __construct(){
        $this->apiModel = new M_api();
        $this->token = $this->apiModel->getToken();
        $this->noticiasModel = new NewsModel();
    }

    // Lista todas as notícias
    public function getAllNews(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $response = $this->noticiasModel->getAllNews();

            // Retornar JSON com status 200
            return response()->json(['noticias' => $response], 200);

        }

    }

    // Registrar notícia
    public function registerNews(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            $request->validate([
                'thumb'     => 'required|mimes:jpg,png,webp|max:2048',
                'webrul'    => 'required|url',
                'sinopse'   => 'required|string|min:20',
                'title'     => 'required|string|min:8',
            ]);

            if(parse_url($request->webrul, PHP_URL_SCHEME)==false |
                parse_url($request->webrul, PHP_URL_HOST)==false){

                return response()->json(['error' => 'A URL informada não é válida!'], 401);
            }

            if ($request->file('thumb')->isValid()){

                $file = $request->file('thumb');
                $path = $request->file('thumb')->storeAs('thumb_', $file->hashName());

            }else{

                return response()->json(['error' => 'Formatos de imagem permitidos: jpg, png, jpeg ou webp.'], 401);

            }

            $hora = time();
            $news = [
                'created_at' => date('Y-m-d H:i:s', $hora),
                'thumb'      => $file->hashName(),
                'intro'      => $request->sinopse,
                'active'     => '1',
                'title'      => $request->title,
                'url'        => $request->webrul,
            ];

            if($this->noticiasModel->insert($news)){

                event(new \App\Events\NewsPublished($news));
                return response()->json(['success' => 'Notícia cadastrada com sucesso!'], 200);

            }

            return response()->json(['error' => 'Houve um erro ao cadastrar notícia!'], 401);

        }
    }

    // Ação de noticia
    public function newsAction(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            switch ($request->submitbutton){

                case 'excluir':
                return $this->deleteNews($request);
                break;
                case 'desativar':default:
                    return  $this->deactivateNews($request);
                break;

            }

        }

    }

    // Deleta notícia
    public function deleteNews(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            // Verifica se o ID da noticia foi informado
            if($request->newsID != null || $request->newsID != ''){

                $files = $this->noticiasModel->getFIles($request->newsID)[0]->thumb;

                if(Storage::exists('thumb_/' . $files)){

                    Storage::delete('thumb_/' . $files);

                }

            }else{

                return response()->json(['error' => 'O ID da notícia é obrigatório!'], 401);

            }

            if($this->noticiasModel->deleteNews($request->newsID)){

                return response()->json(['success' => 'Notícia excluída com sucesso	!'], 200);
            }

            return response()->json(['error' => 'Houve um erro ao excluir noticia!'], 401);

        }

    }

    // Desativa noticia
    public function deactivateNews(Request $request){

        // Verifica se o token da API é valido
        if(!$this->apiModel->validToken($request, $this->token)){

            return response()->json(['error' => 'Token da API inválido!'], 401);

        }else{

            // Verifica se o ID da noticia foi informado
            if($request->newsID != null || $request->newsID != ''){

                if($this->noticiasModel->deactivate($request->newsID)){
                    return response()->json(['success' => 'Notícia desativada com sucesso!'], 200);
                }

                return response()->json(['error' => 'Houve um erro ao desativar noticia!'], 401);

            }

        }

    }

}
