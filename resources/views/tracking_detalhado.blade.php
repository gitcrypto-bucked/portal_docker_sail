@php($perm = new App\Policies\PagePolicy())
@php($perm->userCan( Route::currentRouteName()))
    <!DOCTYPE html>
<html lang="pt_BR" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Ti team &amp; Low Cost contributors">
    <meta name="generator" content="Pedro Henrique & Washington">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Portal LowCost </title>
    <!--  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/jquery-ui.min.css" integrity="sha512-TFee0335YRJoyiqz8hA8KV3P0tXa5CpRBSoM0Wnkn7JoJx1kaq1yXL/rb8YFpWXkMOjRcv5txv+C6UluttluCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/theme.min.css" integrity="sha512-lfR3NT1DltR5o7HyoeYWngQbo6Ec4ITaZuIw6oAxIiCNYu22U5kpwHy9wAaN0vvBj3U6Uy2NNtAfiaKcDxfhTg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/css/dropdown.css')}}">
    <link rel="stylesheet" href="{{asset('/css/search.css')}}">
    <link rel="stylesheet" href="{{asset('/css/list.css')}}">
    <link rel="stylesheet" href="{{asset('/css/tracking.css')}}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('/css/sweetalert.cs    s')}}">

</head>
<body>
<!-- partial:index.partial.html -->
<div id="wrapper">
    <!--sidebar-->
    @include('components.sidebar')

    <!--sidebar-->
    <!--navbar -->
    @include('components.navwrapper')
    <!--content-->

    <div class="container-xxl mt-4 mobile ">
        <h2 class="content-title pageName">Tracking</h2>
        <p class="pageText"></p>

        <div class="row gx-5 gy-3 mt-3 ">
            <div>
                <div class="col-md-8 details_header" >
                    <ul class="list-inline">
                        <li class="list-inline-item">Nota Fiscal:&nbsp;<strong>{!! $pedido[0]->nfe !!}</strong></li>
                        <li class="list-inline-item">Status:&nbsp;<strong class="strong_status">{!! ucfirst($pedido[0]->status_descricao) !!}</strong></li>
                        <li class="list-inline-item" onclick="showModal()">Rastreio:&nbsp;<strong class="strong_status">{!! strtoupper(htmlentities(str_replace('%3d','=',$pedido[0]->tracking_code))) !!}</strong> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--table-->
    <div class="container-xxl mt-4 mobile ">
        <div class="row gx-3 mt-3">
            <!-- gutters -->
            <strong class="mb-3 strong_details">DADOS DA ENTREGA</strong>
            <div class="col border rounded-3 bg-white">
                <ul class="list-group p-4" style="list-style: none; ">
                    <li class="item py-2"><strong>Responsável:&nbsp;</strong>{!! strtoupper($pedido[0]->responsavel) !!}</li>
                    <li class="item py-2"><strong>Endereço de Entrega:&nbsp;</strong>{!! ucwords($pedido[0]->endereco_entrega) !!}, {!! strtoupper($pedido[0]->numero) !!}, {!! ucwords($pedido[0]->bairro) !!} , {{ \Helpers\Helpers::formatCidade($pedido[0]->cidade) }} - {!! ucwords($pedido[0]->estado) !!}, {!! \Helpers\Helpers::formatCEP($pedido[0]->cep) !!}</li>
                    <li class="item py-2"><strong>Data da Postagem:&nbsp;</strong> {!! date('d/m/Y', strtotime(str_replace('-','',$pedido[0]->data_faturamento))) !!}</li>
                    <li class="item py-2"><strong>Data da Entrega:&nbsp;</strong> {!! date('d/m/Y', strtotime(str_replace('-','',$pedido[0]->data_ocorrencia))) !!}</li>
                    <li class="item py-2"><strong>Recebido por:&nbsp;</strong> {!! ucfirst($pedido[0]->recebedor)!!}</li>
                </ul>
            </div>
            <!-- gutters-->
        </div>
    </div>

    <div class="container-xxl mt-4 mobile ">
        <div class="row mt-3">
            <!-- gutters -->
                <strong class="mb-3 strong_details">PRODUTO(S)</strong>
                <br>
                <div class="col-12 details_header"  style="background-color: transparent!important">
                    <ul class="list-inline">
                        <li class="w-100 d-flex justify-content-between">
                            <div>
                                <strong>Código</strong>
                            </div>
                            <div>
                                <strong>Descricão do Produto(s)</strong>
                            </div>
                            <div>
                                <strong>Quantidade</strong>
                            </div>

                        </li>
                    </ul>
                </div>
                <hr>
                <table class="table_list">
                    @php($cod =0)
                    @for($i=0; $i<sizeof($pedido); $i++)
                            @if($cod != $pedido[$i]->codigoProduto)
                                <tr class="card my-2">
                                    <td class="w-100 d-flex justify-content-between card-header py-4">
                                        <div>
                                            <abbr  class="initialism">{!! $pedido[$i]->codigoProduto !!}</abbr>
                                        </div>
                                        <div>
                                            <abbr  class="initialism">{!!ucfirst( $pedido[$i]->descricao) !!}</abbr>
                                        </div>
                                        <div>
                                            <abbr title="HyperText Markup Language" class="initialism">{!! $pedido[$i]->quantidade !!}&nbsp;</abbr>
                                        </div>

                                    </td>
                                </tr>
                            @endif
                        @php($cod = $pedido[$i]->codigoProduto)
                    @endfor
                </table>
            <!-- gutters-->
        </div>
    </div>
    <!--wrapper-->
    <!--modal -->
    <div class="modal modal-lg" tabindex="-1" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                    <!-- Tabs n avs -->

                    <!-- end -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!--modal end-->
</div>
</body>
<script  src="{{asset('/js/script.js')}}"></script>
<script  src="{{asset('/js/main.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.i18n/1.0.9/jquery.i18n.js" integrity="sha512-OU7TUd3qODSbwnF5mHouW66/Yt54mzh58k+rxfJili7AunMl5wbF2D8kLGTGuUv5xM2MfylWZOPZaqOKpf7dZg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
function showModal()
{
    $("#myModal").modal('toggle');
}
</script>
</html>
